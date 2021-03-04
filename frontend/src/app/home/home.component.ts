import { Component, OnInit, Input } from '@angular/core'
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { Cookie } from 'ng2-cookies/ng2-cookies'
import { HomeService } from './home.service'
import { Config } from './../config/config'
import { Router, ActivatedRoute } from '@angular/router'
import { Observable } from 'rxjs/Observable'
import 'rxjs/add/observable/of'
import 'rxjs/add/operator/delay'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
declare var jQuery: any

function passwordConfirming(c: AbstractControl): any {
	if (!c.parent || !c) return

	const pwd = c.parent.get('password')
	const cpwd = c.parent.get('confPassword')

	if (!pwd || !cpwd) return

	if (pwd.value !== cpwd.value) {
		return { invalid: true }
	}
}

@Component({
	selector: 'app-root',
	templateUrl: './home.component.html',
	styleUrls: ['./home.component.css'],
})
export class HomeComponent implements OnInit {
	public login: boolean = false
	public register: boolean = true
	public forgetPwd: boolean = false

	public haveQuestionResponseText: String = ''
	public registerResponseText: String = ''
	public loginResponseText: String = ''
	public frgtPwdResponseText: String = ''
	public registerStatus: Number = 0
	public frgtPwdStatus: Number = 0
	public params: any

	public registerForm: FormGroup
	public loginForm: FormGroup
	public frgtPwdForm: FormGroup
	public questionForm: FormGroup
	public communityForm: FormGroup

	public users: any = 0
	public stage: String = ''
	public soldCoins: any = 0

	public communityResponse: any

	constructor(private formBuilder: FormBuilder, private homeService: HomeService, private router: Router, private spinnerService: Ng4LoadingSpinnerService, private routeParams: ActivatedRoute) {}

	get cpwd() {
		return this.registerForm.get('confPassword')
	}

	ngOnInit() {
		//
		this.registerForm = new FormGroup({
			username: new FormControl('', [Validators.required, Validators.minLength(5)]),
			email: new FormControl('', [Validators.required, Validators.email]),
			password: new FormControl('', [Validators.required, Validators.minLength(5)]),
			confPassword: new FormControl('', [Validators.required, passwordConfirming]),
		})

		this.loginForm = this.formBuilder.group({
			username: [null, Validators.required],
			password: [null, Validators.required],
			tfa_key: [null, []],
		})

		this.questionForm = this.formBuilder.group({
			name: [null, Validators.required],
			email: [null, [Validators.required, Validators.email]],
			message: [null, Validators.required],
		})

		this.communityForm = this.formBuilder.group({
			name: [null, Validators.required],
			email: [null, [Validators.required, Validators.email]],
		})

		this.frgtPwdForm = this.formBuilder.group({
			email: [null, Validators.required],
		})

		if (this.router.url == '/home/login') {
			this.showLogin()
		}

		this.getData()

		jQuery('#WelcomeModal').modal('show')
		jQuery('#home_video')[0].play()
	}

	getData() {
		this.homeService.getData().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.users = obj.data.users
				this.soldCoins = obj.data.soldCoins
				this.stage = obj.data.stage
			}
		})
	}

	showLogin() {
		this.login = true
		this.register = false
		this.forgetPwd = false
		jQuery('#login-modal').modal('show')
	}

	showRegister() {
		this.login = false
		this.register = true
		this.forgetPwd = false
	}

	showForgotPassword() {
		this.login = false
		this.register = false
		this.forgetPwd = true
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

	isFieldValid(field: string, form: any) {
		return !form.get(field).valid && form.get(field).touched
	}

	displayFieldCss(field: string, form: any) {
		return {
			'has-error': this.isFieldValid(field, form),
			'has-feedback': this.isFieldValid(field, form),
		}
	}

	registerSubmit(data) {
		if (this.registerForm.valid) {
			if (Cookie.get('referral') != null) {
				data.referral = Cookie.get('referral')
			}
			this.spinnerService.show()
			this.homeService.register(data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.registerResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.registerStatus = obj.status
					localStorage.setItem('user_id', obj.data.user_id)
					this.registerForm.reset()
				}
			})
		} else {
			this.validateAllFormFields(this.registerForm)
		}
	}

	loginSubmit(data) {
		if (this.loginForm.valid) {
			this.spinnerService.show()
			this.homeService.login(data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.loginResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.loginForm.reset()
					localStorage.setItem('token', obj.data.token)
					localStorage.setItem('user_id', obj.data.user_id)
					localStorage.setItem('username', obj.data.username)
					setTimeout((router: Router) => {
						jQuery('#login-modal').modal('hide')
						this.router.navigate(['dashboard'])
					}, 3000)
				}
			})
		} else {
			this.validateAllFormFields(this.loginForm)
		}
	}

	frgtPwdSubmit(data) {
		if (this.frgtPwdForm.valid) {
			this.spinnerService.show()
			this.homeService.frgtPwd(data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.frgtPwdResponseText = '<p class="alert alert-' + obj.class + '">' + obj.message + '</p>'
				if (obj.status == Config.SUCCESS_CODE) {
					this.frgtPwdStatus = obj.status
					localStorage.setItem('user_id', obj.data.user_id)
					this.frgtPwdForm.reset()
				}
			})
		} else {
			this.validateAllFormFields(this.frgtPwdForm)
		}
	}

	resendEmail(type) {
		this.spinnerService.show()

		this.homeService.resendEmail({ type: type, id: localStorage.getItem('user_id') }).subscribe((response) => {
			this.spinnerService.hide()
			let obj: any = JSON.parse(response._body)

			if (type == 'register') {
				this.registerResponseText = obj.message
			} else {
				this.frgtPwdResponseText = obj.message
			}
		})
	}

	scroll(el) {
		el.scrollIntoView()
	}

	haveQuestionSubmit(data: any) {
		this.spinnerService.show()

		this.homeService.haveQuestion(data).subscribe((response) => {
			this.spinnerService.hide()
			let obj: any = JSON.parse(response._body)
			//this.haveQuestionResponseText = obj.message
			this.questionForm.reset()
			jQuery('#question-modal').modal('hide')
		})
	}

	showExchangeModal() {
		jQuery('#ExchangeModal').modal('show')
	}

	community(data: any) {
		this.homeService.community(data).subscribe((response) => {
			this.communityResponse = response._body
		})
	}
}
