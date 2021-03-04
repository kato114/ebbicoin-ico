import { Component } from '@angular/core'
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { Router } from '@angular/router'
import { MyAccountService } from './my-account.service'
import { Config } from './../config/config'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
declare var jQuery: any

function passwordConfirming(c: AbstractControl): any {
	if (!c.parent || !c) return

	const pwd = c.parent.get('newPassword')
	const cpwd = c.parent.get('confPassword')

	if (!pwd || !cpwd) return

	if (pwd.value !== cpwd.value) {
		return { invalid: true }
	}
}

@Component({
	selector: 'my-app',
	templateUrl: './my-account.component.html',
})
export class MyAccountComponent {
	public user: any
	public myAccountForm: any
	public tfaForm: any
	public tfaDisableForm: any
	public tfaDeactivateForm: any
	public changePwdForm: any
	public myAccountResponseText: any
	public tfaResponseText: any
	public changePwdResponseText: any
	public tfa_qr: any
	public tfa_status: any
	public tfa_key: any

	constructor(private router: Router, private myAccountService: MyAccountService, private spinnerService: Ng4LoadingSpinnerService) {}

	get cpwd() {
		return this.changePwdForm.get('confPassword')
	}

	ngOnInit() {
		this.myAccountForm = new FormGroup({
			referral: new FormControl('', []),
			username: new FormControl('', [Validators.required, Validators.minLength(5)]),
			email: new FormControl('', [Validators.required, Validators.email]),
			phone: new FormControl('', [Validators.required]),
		})

		this.tfaForm = new FormGroup({
			tfa_key: new FormControl('', []),
			authencode: new FormControl('', [Validators.required]),
		})

		this.tfaDisableForm = new FormGroup({
			tfa_key: new FormControl('', []),
			password: new FormControl('', [Validators.required]),
			authencode: new FormControl('', [Validators.required]),
		})

		this.tfaDeactivateForm = new FormGroup({
			tfa_key: new FormControl('', []),
			authencode: new FormControl('', [Validators.required]),
		})

		this.changePwdForm = new FormGroup({
			currentPassword: new FormControl('', [Validators.required]),
			newPassword: new FormControl('', [Validators.required]),
			confPassword: new FormControl('', [Validators.required, passwordConfirming]),
		})

		this.getUserDetail()
	}

	getUserDetail() {
		this.myAccountService.getUser(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				this.user = obj.data

				this.tfa_status = this.user.tfa_status
				this.tfa_key = this.user.tfa_key
				this.myAccountForm.controls['referral'].setValue(this.user.referral)
				this.myAccountForm.controls['username'].setValue(this.user.username)
				this.myAccountForm.controls['email'].setValue(this.user.email)
				this.myAccountForm.controls['phone'].setValue(this.user.phone)

				this.tfaForm.controls['tfa_key'].setValue(this.user.tfa_key)

				this.tfaDisableForm.controls['tfa_key'].setValue(this.user.tfa_key)

				this.tfaDeactivateForm.controls['tfa_key'].setValue(this.user.tfa_key)

				this.getTwoFactorAuthQRCode()
			}
		})
	}

	getTwoFactorAuthQRCode() {
		this.myAccountService.twoFactorAuthQRCode(this.user.username, this.user.tfa_key).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.tfa_qr = obj.data.link
		})
	}

	updateAccount(data) {
		if (this.myAccountForm.valid) {
			this.spinnerService.show()
			this.myAccountService.updateAccount(localStorage.getItem('user_id'), data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.myAccountResponseText = obj.message
			})
		} else {
			this.validateAllFormFields(this.myAccountForm)
		}
	}

	enable2FA(data) {
		if (this.tfaForm.valid) {
			this.spinnerService.show()
			console.log(data)
			this.myAccountService.enable2FA(localStorage.getItem('user_id'), data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.tfaResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.tfaForm.controls['authencode'].setValue('')
					this.tfa_status = 1
				}
			})
		} else {
			this.validateAllFormFields(this.tfaForm)
		}
	}

	disable2FA(data) {
		if (this.tfaDisableForm.valid) {
			this.spinnerService.show()
			this.myAccountService.disable2FA(localStorage.getItem('user_id'), data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.tfaResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.tfaDisableForm.controls['authencode'].setValue('')
					this.tfaDisableForm.controls['password'].setValue('')
					this.tfa_status = 2
				}
			})
		} else {
			this.validateAllFormFields(this.tfaDisableForm)
		}
	}

	deactivate2FA(data) {
		if (this.tfaDeactivateForm.valid) {
			this.spinnerService.show()
			this.myAccountService.deactivate2FA(localStorage.getItem('user_id'), data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.tfaResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.tfaDeactivateForm.controls['authencode'].setValue('')
					this.tfa_status = 1
				}
			})
		} else {
			this.validateAllFormFields(this.tfaDeactivateForm)
		}
	}

	changePassword(data) {
		console.log(this.changePwdForm)
		if (this.changePwdForm.valid) {
			this.spinnerService.show()
			this.myAccountService.changePassword(localStorage.getItem('user_id'), data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.changePwdResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.changePwdForm.reset()
				}
			})
		} else {
			this.validateAllFormFields(this.tfaForm)
		}
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
}
