import { Component } from '@angular/core'
import { Router } from '@angular/router'
import { AdminCommonService } from './../admin-common.service'
import { Config } from './../../../config/config'

@Component({
	selector: 'admin-sidebar',
	templateUrl: './sidebar.component.html',
	styleUrls: ['./sidebar.component.css'],
})
export class AdminSidebarComponent {
	public username: any
	public page: String

	constructor(private commonService: AdminCommonService, private router: Router) {
		console.log(router.url)

		this.username = 'admin'
		this.page = router.url
	}

	logout() {
		this.commonService.logout({}).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				localStorage.clear()
				this.router.navigate(['admin'])
			}
		})
	}
}
