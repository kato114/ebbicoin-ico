import { Component, OnInit, Input } from '@angular/core';
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms';
import { HomeService } from './../home/home.service';
import { Config } from './../config/config';
import { Router, ActivatedRoute } from '@angular/router';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import 'rxjs/add/operator/delay';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
declare var jQuery:any;

function passwordConfirming(c: AbstractControl): any {
    
    if( !c.parent || !c ) return;

    const pwd = c.parent.get('password');
    const cpwd= c.parent.get('confPassword')

    if(!pwd || !cpwd) return ;

    if (pwd.value !== cpwd.value) {
        return { invalid: true };
    }
}

@Component({
  selector: 'app-root',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})

export class RegisterComponent implements OnInit {

  public registerResponseText : String = '';
  public loginResponseText    : String = '';
  public frgtPwdResponseText  : String = '';
  public registerStatus       : Number = 0;
  public frgtPwdStatus        : Number = 0;
  public params               : any;

  public registerForm : FormGroup;

  constructor(
    private formBuilder: FormBuilder, 
    private homeService: HomeService,
    private routeParams: ActivatedRoute,
    private router: Router,
    private spinnerService: Ng4LoadingSpinnerService
  ){
    this.routeParams.params.subscribe(params => {
      this.params = params;
    });
  }

  get cpwd() {
      return this.registerForm.get('confPassword');
  }

  ngOnInit() {
  
    this.registerForm = new FormGroup({
      referral    : new FormControl(this.params.referral, [Validators.required]),
      username    : new FormControl('', [Validators.required, Validators.minLength(5)]),
      email       : new FormControl('', [Validators.required, Validators.email]),
      password    : new FormControl('', [Validators.required, Validators.minLength(5)]),
      confPassword: new FormControl('', [Validators.required, passwordConfirming]),
    });

  }

  validateAllFormFields(formGroup: FormGroup) {
    Object.keys(formGroup.controls).forEach(field => {
      const control = formGroup.get(field); 
      if (control instanceof FormControl) { 
        control.markAsTouched({ onlySelf: true });
      } else if (control instanceof FormGroup) {
        this.validateAllFormFields(control); 
      }
    });
  }

  isFieldValid(field: string, form: any) {
    return !form.get(field).valid && form.get(field).touched;
  }

  displayFieldCss(field: string, form: any) {
    return {
      'has-error': this.isFieldValid(field, form),
      'has-feedback': this.isFieldValid(field, form)
    };
  }

  registerSubmit(data) {
    if (this.registerForm.valid) {
      this.spinnerService.show();
      this.homeService.register(data)
      .subscribe(response => {
        this.spinnerService.hide();
        let obj:any = JSON.parse(response._body);
        this.registerResponseText = obj.message;
        if(obj.status == Config.SUCCESS_CODE){
          this.registerStatus = obj.status;
          localStorage.setItem('user_id', obj.data.user_id);
          this.registerForm.reset();
          this.registerForm.controls['referral'].setValue( this.params.referral );
        }
      });
    } else {
      this.validateAllFormFields(this.registerForm);
    }
  }

  resendEmail( type ){

    this.spinnerService.show();

    console.log( localStorage.getItem('user_id') );

    this.homeService.resendEmail( { 'type' : type, 'id': localStorage.getItem('user_id') } )
    .subscribe(response => {

      this.spinnerService.hide();
      let obj:any = JSON.parse(response._body);

      if( type == 'register' ){
        this.registerResponseText = obj.message;
      } else {
        this.frgtPwdResponseText  = obj.message;
      }

    });

  }

}
