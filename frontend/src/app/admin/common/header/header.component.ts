import { Component } from '@angular/core';
import { AdminCommonService } from './../admin-common.service';
import { Router } from '@angular/router';
import { Config } from './../../../config/config';
import { of } from 'rxjs/observable/of';
declare var jQuery:any;

@Component({
    selector: 'admin-header',
    templateUrl: './header.component.html',
    styleUrls: ['./header.component.css']
})

export class AdminHeaderComponent {

    public user : any;

    constructor(
        private commonService: AdminCommonService,
        private router: Router
    ){
        
        jQuery('.navbar-toggle').removeClass('toggled');
        jQuery('html').removeClass('nav-open');

        if(localStorage.getItem('status') == '1'){
            this.user = { "username": "admin" };
            let user = { };
            this.commonService.loginStatus( user )
            .subscribe(response => {
                let obj:any = JSON.parse(response._body);
                if(obj.status != Config.SUCCESS_CODE){
                    this.router.navigate(['admin']);
                }
            });
        } else {
            this.logout();
            localStorage.clear();
            this.router.navigate(['admin']);
        }
        
    }

    sidebar() {
        if( jQuery('#minimizeSidebar').find('i.fa').hasClass('fa-list-ul') ){
            jQuery('#minimizeSidebar').find('i.fa').removeClass('fa-list-ul');
            jQuery('#minimizeSidebar').find('i.fa').addClass('fa-ellipsis-v');
            jQuery('body').removeClass('sidebar-mini');
        } else {
            jQuery('#minimizeSidebar').find('i.fa').removeClass('fa-ellipsis-v');
            jQuery('#minimizeSidebar').find('i.fa').addClass('fa-list-ul');
            jQuery('body').addClass('sidebar-mini');
        }
    }

    mobileSidebar(){
        console.log('click');
        if( jQuery('.navbar-toggle').hasClass('toggled') ){
            jQuery('.navbar-toggle').removeClass('toggled');
            jQuery('html').removeClass('nav-open');
        } else {
            jQuery('.navbar-toggle').addClass('toggled');
            jQuery('html').addClass('nav-open');
        }
    }

    logout() {
        this.commonService.logout( { } )
        .subscribe(response => {
            let obj:any = JSON.parse(response._body);
            if(obj.status == Config.SUCCESS_CODE){
                localStorage.clear();
                this.router.navigate(['admin']);
            }
        });
    }

}
