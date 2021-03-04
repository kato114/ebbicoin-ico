import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms';
import { AdminLoginService } from './login.service';
import { Config } from './../../config/config';
import { Router } from '@angular/router';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import 'rxjs/add/operator/delay';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
declare var jQuery:any;
import { AdminCommonService } from './../common/admin-common.service';

@Component({
    selector: 'my-app',
    templateUrl: './login.component.html'
})

export class AdminLoginComponent {

    public loginForm    : FormGroup;
    public loginResponseText    : string = '';

    constructor(
      private formBuilder: FormBuilder, 
      private loginService: AdminLoginService,
      private router: Router,
      private spinnerService: Ng4LoadingSpinnerService,
      private commonService: AdminCommonService,
    ){ }

    ngOnInit() {

        this.loginForm = this.formBuilder.group({
            username: [null, Validators.required],
            password: [null, Validators.required],
        });

        if( localStorage.getItem('status') == '1' ){
        
          this.commonService.loginStatus( { } )
          .subscribe(response => {
              let obj:any = JSON.parse(response._body);
              if(obj.status == Config.SUCCESS_CODE){
                this.router.navigate(['admin/dashboard']);
              }
          });

        }

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

    loginSubmit(data) {
      if (this.loginForm.valid) {
        this.spinnerService.show();
        this.loginService.login(data)
        .subscribe(response => {
          this.spinnerService.hide();
          let obj:any = JSON.parse(response._body);
          this.loginResponseText = obj.message;
          if(obj.status == Config.SUCCESS_CODE){
            localStorage.setItem('status', '1');
            this.loginForm.reset();
            setTimeout((router: Router) => {
              jQuery("#login-modal").modal("hide");
              this.router.navigate(['admin/dashboard']);
            }, 3000);
          }
        });
      } else {
        this.validateAllFormFields(this.loginForm);
      }
    }

}
