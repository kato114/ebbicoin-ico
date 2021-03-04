import { Component } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms';
import { SupportService } from './../support.service';
import { Config } from './../../config/config';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
declare var jQuery:any;

@Component({
    selector: 'my-app',
    templateUrl: './view-tickets.component.html'
})

export class SupportsViewComponent {
    
    public url = Config.API;
    public commentForm: any;
    public commentResponseText: any;

    public ticket: any;
    public comments: any;
    public is_busy: boolean = true;

    private params: any;

    constructor(
        private router: Router,
        private routeParams: ActivatedRoute,
        private supportService: SupportService,
        private spinnerService: Ng4LoadingSpinnerService
    ){ 
        this.routeParams.params.subscribe(params => {
            this.params = params;
        });

        this.getTicket();
    }

    ngOnInit(){
  
        this.commentForm = new FormGroup({
            ticket_id   : new FormControl(this.params.id, [Validators.required]),
            user_id     : new FormControl(localStorage.getItem('user_id'), [Validators.required]),
            comment     : new FormControl('', [Validators.required]),
            image       : new FormControl('', []),
        });

    }

    getTicket(){
        this.supportService.getTicket( this.params.id )
        .subscribe(response => {
            let obj:any = JSON.parse(response._body);
            this.ticket = obj.data.ticket;
            this.comments = obj.data.comments;
            this.is_busy = false;
        })
    }

    addNewComment(data) {
        if (this.commentForm.valid) {
            this.spinnerService.show();
            this.supportService.addComment(data)
            .subscribe(response => {
                this.spinnerService.hide();
                let obj:any = JSON.parse(response._body);
                this.commentResponseText = obj.message;
                if( obj.status == Config.SUCCESS_CODE ){
                    this.getTicket();
                    this.clearForm();
                }
            });
        } else {
            this.validateAllFormFields(this.commentForm);
        }
    }

    clearForm () {
        this.commentForm.controls['comment'].setValue('');
        this.commentForm.controls['image'].setValue('');
        jQuery('#commentImage').wrap('<form>').closest('form').get(0).reset();
        jQuery('#commentImage').unwrap();
    }

    onFileChange(event) {
        let reader = new FileReader();
        if(event.target.files && event.target.files.length > 0) {
            let file = event.target.files[0];
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.commentForm.get('image').setValue({
                    filename: file.name,
                    filetype: file.type,
                    value: reader.result.split(',')[1]
                })
            };
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

}
