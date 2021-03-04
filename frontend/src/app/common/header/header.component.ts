import { Component } from '@angular/core'
import { HeaderService } from './header.service'
import { Router, ActivatedRoute } from '@angular/router'
import { Config } from './../../config/config'
import { of } from 'rxjs/observable/of'
declare var jQuery: any

@Component({
	selector: 'app-header',
	templateUrl: './header.component.html',
	styleUrls: ['./header.component.css'],
})
export class HeaderComponent {
	public user: any
	public rate: any = { btc: 0, eth: 0, bch: 0, ebbi: 0.7 }
	public url: any

	constructor(private headerService: HeaderService, private router: Router, private routeParams: ActivatedRoute) {
		this.url = this.router.url
		this.url = this.url.replace('/', '').replace('-', ' ').replace('-', ' ')
		this.url = this.url.charAt(0).toUpperCase() + this.url.slice(1)

		this.headerService.getRate().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.rate.btc = parseFloat(obj.bitcoin.usd)
			this.rate.eth = parseFloat(obj.ethereum.usd)
			this.rate.bch = parseFloat(obj.bitcash.usd)
		})

		this.headerService.getCoinPrice().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.rate.ebbi = parseFloat(obj.data.price)
		})
	}

	ngOnInit() {
		jQuery('.navbar-toggle').removeClass('toggled')
		jQuery('html').removeClass('nav-open')

		this.user = { username: 'ebbicoin' }
		if (localStorage.getItem('token') && localStorage.getItem('user_id')) {
			let user = { token: localStorage.getItem('token'), user_id: localStorage.getItem('user_id') }
			this.headerService.loginStatus(user).subscribe((response) => {
				let obj: any = JSON.parse(response._body)
				if (obj.status == Config.SUCCESS_CODE) {
					this.user = obj.data
				} else {
					this.router.navigate(['home'])
				}
			})
		} else {
			this.router.navigate(['home'])
		}
	}

	sidebar() {
		if (jQuery('#minimizeSidebar').find('i.fa').hasClass('fa-list-ul')) {
			jQuery('#minimizeSidebar').find('i.fa').removeClass('fa-list-ul')
			jQuery('#minimizeSidebar').find('i.fa').addClass('fa-ellipsis-v')
			jQuery('body').removeClass('sidebar-mini')
		} else {
			jQuery('#minimizeSidebar').find('i.fa').removeClass('fa-ellipsis-v')
			jQuery('#minimizeSidebar').find('i.fa').addClass('fa-list-ul')
			jQuery('body').addClass('sidebar-mini')
		}
	}

	mobileSidebar() {
		if (jQuery('.navbar-toggle').hasClass('toggled')) {
			jQuery('.navbar-toggle').removeClass('toggled')
			jQuery('html').removeClass('nav-open')
		} else {
			jQuery('.navbar-toggle').addClass('toggled')
			jQuery('html').addClass('nav-open')
		}
	}

	logout() {
		localStorage.clear()
		this.router.navigate(['home'])
	}
}
