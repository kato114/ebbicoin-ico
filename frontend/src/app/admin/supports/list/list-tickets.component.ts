import { Component, AfterViewInit } from '@angular/core';
import { Router } from '@angular/router';
import { AdminSupportService } from './../support.service';
import { Config } from './../../../config/config';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
declare var jQuery:any;

@Component({
    selector: 'my-app',
    templateUrl: './list-tickets.component.html'
})

export class AdminSupportsListComponent {
    
    public supportForm: any;
    public supportResponseText: any;
    public term : any;
    public tickets: any;

    constructor(
        private router: Router,
        private supportService: AdminSupportService,
        private spinnerService: Ng4LoadingSpinnerService
    ){ }

    ngOnInit(){
        this.getTickets();
    }

    getTickets(){
        this.supportService.getTickets()
        .subscribe(response => {
            let obj:any = JSON.parse(response._body);
            if( obj.status == Config.SUCCESS_CODE ){
                this.tickets = obj.data;
            }
        });
    }

    closeTicket( ticket_id: any ){
        this.supportService.closeTicket( ticket_id )
        .subscribe(response => {
            this.getTickets();
        });
    }

}
