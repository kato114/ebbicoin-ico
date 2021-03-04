import { Component, OnInit, Input } from '@angular/core';
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms';
import { AdminUsersService } from './../users.service';
import { Config } from './../../../config/config';
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
    selector: 'my-app',
    templateUrl: './user-add.component.html',
    styleUrls: ['./user-add.component.css']
})

export class AdminUserAddComponent {

    public username: any;
    public userFormResponseText : String = '';
    public userForm : FormGroup;

    get cpwd() {
        return this.userForm.get('confPassword');
    }

    constructor(
        private userService: AdminUsersService,
        private formBuilder: FormBuilder,
        private spinnerService: Ng4LoadingSpinnerService
    ){
        this.username = localStorage.getItem('username');
    }

    ngOnInit() {
  
      this.userForm = new FormGroup({
        username    : new FormControl('', [Validators.required, Validators.minLength(5)]),
        email       : new FormControl('', [Validators.required, Validators.email]),
        password    : new FormControl('', [Validators.required, Validators.minLength(5)]),
        confPassword: new FormControl('', [Validators.required, Validators.minLength(5), passwordConfirming]),
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
  
    addUser(data) {
        if (this.userForm.valid) {
            this.spinnerService.show();
            this.userService.addUser(data)
            .subscribe(response => {
                this.spinnerService.hide();
                let obj:any = JSON.parse(response._body);
                this.userFormResponseText = obj.message;
                setTimeout((router: Router) => {
                    this.userFormResponseText = '';
                }, 3000);
            });
        } else {
            this.validateAllFormFields(this.userForm);
        }
    }

}
