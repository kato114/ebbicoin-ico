import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
    selector: 'app-sidebar',
    templateUrl: './sidebar.component.html',
    styleUrls: ['./sidebar.component.css']
})

export class SidebarComponent {
    
    public username: any;
    public page : String;

    constructor(
        private router: Router
    ){
        this.username = localStorage.getItem('username');
        this.page = router.url;
    }

    logout() {
        localStorage.clear();
        this.router.navigate(['home']);
    }

}
