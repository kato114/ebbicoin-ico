import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { SupportService } from './../support.service';
import { Config } from './../../config/config';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
declare var jQuery:any;

@Component({
    selector: 'my-app',
    templateUrl: './list-tickets.component.html'
})

export class SupportsListComponent {
    
    public supportForm: any;
    public supportResponseText: any;

    public tickets: any;

    constructor(
        private router: Router,
        private supportService: SupportService,
        private spinnerService: Ng4LoadingSpinnerService
    ){ }

    ngOnInit(){

        this.supportService.getTickets( localStorage.getItem('user_id') )
        .subscribe(response => {
            let obj:any = JSON.parse(response._body);
            this.tickets = obj.data;
        })

    }

}
