import { Component } from '@angular/core';
import { AdminCommonService } from '../common/admin-common.service';
import { FormBuilder, Validators, FormGroup, FormControl } from '@angular/forms';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router } from '@angular/router';
import { Config } from '../../config/config';
declare var jQuery: any;

@Component({
    selector: 'my-app',
    templateUrl: './change-password.component.html'
})

export class AdminChangePasswordComponent {

    public passwordForm: any;
    public responseText: any;

    constructor(
        private router: Router,
        private formBuilder: FormBuilder,
        private commonService: AdminCommonService,
        private spinnerService: Ng4LoadingSpinnerService
    ){ }  

    ngOnInit() {

        this.passwordForm = this.formBuilder.group({
            password        : [null, Validators.required],
            password_conf   : [null, Validators.required],
        });

    }
  
    isFieldValid(field: string, form: any) {
        return !form.get(field).valid && form.get(field).touched;
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

    passwordSubmit(data: any){
        if (this.passwordForm.valid) {

            if( this.passwordForm.get('password').value == this.passwordForm.get('password_conf').value ){
            
                this.spinnerService.show();

                this.commonService.changePassword(data)
                .subscribe(response => {

                    this.spinnerService.hide();
                    let obj:any = JSON.parse(response._body);
                    this.responseText = '<p class="alert alert-' + obj.class + '">' + obj.message + '</p>';

                    if(obj.status == Config.SUCCESS_CODE){
                        this.passwordForm.reset();
                    }

                });

            } else {
                this.responseText = '<p class="alert alert-danger">Confirm password must be same as passsword.</p>';
            }

        } else {
            this.validateAllFormFields(this.passwordForm);
        }
    }

}