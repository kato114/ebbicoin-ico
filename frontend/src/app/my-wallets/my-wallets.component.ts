import { Component } from '@angular/core'
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { Router } from '@angular/router'
import { MyWalletsService } from './my-wallets.service'
import { Config } from './../config/config'
import { DashboardService } from '../dashboard/dashboard.service'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
import { DomSanitizer } from '@angular/platform-browser'
declare var jQuery: any

@Component({
	selector: 'my-app',
	templateUrl: './my-wallets.component.html',
	styleUrls: ['./my-wallets.component.css'],
})
export class MyWalletsComponent {
	public username: any
	public ebbiBalance: Number
	public balance: any = { btc_balance: 'loading...', eth_balance: 'loading...', bch_balance: 'loading...', ebbi_balance: 'loading...' }
	public user: any = {
		eth_address: '',
	}
	public ethIframe: any
	public url: any = Config.API

	public transferForm: FormGroup
	public transferEtherForm: FormGroup
	public loginResponseText: String = ''
	public sendEthereumText: String = ''

	constructor(private formBuilder: FormBuilder, private router: Router, private spinnerService: Ng4LoadingSpinnerService, private myWalletsService: MyWalletsService, private dashboardService: DashboardService, private sanitizer: DomSanitizer) {}

	ngOnInit() {
		this.getUserDetail()
		this.getUserBalance()

		this.transferForm = this.formBuilder.group({
			username: [null, Validators.required],
			password: [null, Validators.required],
			amount: [null, [Validators.required, Validators.min(0)]],
		})

		this.transferEtherForm = this.formBuilder.group({
			address: [null, Validators.required],
			password: [null, Validators.required],
			amount: [null, [Validators.required, Validators.min(0)]],
		})
	}

	getUserBalance() {
		this.dashboardService.getUserBalance({ user_id: localStorage.getItem('user_id') }).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				this.balance = obj.data
			}
		})
	}

	getUserDetail() {
		this.dashboardService.getUser(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				this.user.eth_address = obj.data.eth_address1
				this.ethIframe = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + this.user.eth_address
				this.ethIframe = this.sanitizer.bypassSecurityTrustResourceUrl(this.ethIframe)
			}
		})
	}

	showAddressModal() {
		jQuery('#AddressModal').modal('show')
	}

	hideAddressModal() {
		jQuery('#AddressModal').modal('hide')
	}

	validateAllFormFields(formGroup: FormGroup) {
		Object.keys(formGroup.controls).forEach((field) => {
			const control = formGroup.get(field)
			if (control instanceof FormControl) {
				control.markAsTouched({ onlySelf: true })
			} else if (control instanceof FormGroup) {
				this.validateAllFormFields(control)
			}
		})
	}

	transferCoin(data) {
		if (this.transferForm.valid) {
			this.myWalletsService.transfer(data).subscribe((response) => {
				let obj: any = JSON.parse(response._body)
				this.loginResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.getUserBalance()
					this.transferForm.reset()
					setTimeout((router: Router) => {
						this.loginResponseText = ''
					}, 10000)
				}
			})
		} else {
			this.validateAllFormFields(this.transferForm)
		}
	}

	transferEther(data) {
		if (this.transferEtherForm.valid) {
			this.spinnerService.show()
			this.myWalletsService.transferEther(data).subscribe((response) => {
				let obj: any = JSON.parse(response._body)
				this.sendEthereumText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.transferEtherForm.reset()
					setTimeout((router: Router) => {
						this.sendEthereumText = ''
					}, 10000)
				}
				this.spinnerService.hide()
			})
		} else {
			this.validateAllFormFields(this.transferEtherForm)
		}
	}

	createEthereumWallet() {
		this.spinnerService.show()
		this.dashboardService.createWallet(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				this.user.eth_address = obj.data.eth_address1
				this.ethIframe = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + this.user.eth_address
				this.ethIframe = this.sanitizer.bypassSecurityTrustResourceUrl(this.ethIframe)
			}
			this.spinnerService.hide()
		})
	}

	copyAddress() {
		let temp = jQuery('<input>')
		$('body').append(temp)
		temp.val($('#ETHAddress').val()).select()
		document.execCommand('copy')
		temp.remove()
		alert('Your ethereum address copied to clipboard!')
	}
}
