import { Component, OnInit, Input } from '@angular/core'
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { AdminUsersService } from './../users.service'
import { Config } from './../../../config/config'
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
	selector: 'my-app',
	templateUrl: './user-edit.component.html',
	styleUrls: ['./user-edit.component.css'],
})
export class AdminUserEditComponent {
	public username: any
	public userFormResponseText: String = ''
	public userForm: FormGroup
	private params: any
	public transactions: any

	public teamMembers: any = []
	public level1: any
	public level2: any
	public level3: any
	public referral_income: any
	public referral_status: boolean = false
	public referral: any
	public totalTeamMembers: any
	public totalTeamPurchase: any
	public totalTeamIncome: any
	public pusername: any

	constructor(private router: Router, private userService: AdminUsersService, private formBuilder: FormBuilder, private routeParams: ActivatedRoute, private spinnerService: Ng4LoadingSpinnerService) {
		this.username = localStorage.getItem('username')
	}

	ngOnInit() {
		this.routeParams.params.subscribe((params) => {
			this.params = params
		})

		this.userForm = this.formBuilder.group({
			username: [null, Validators.required],
			email: [null, [Validators.required, Validators.email]],
			password: [null, []],
		})

		this.userForm = new FormGroup({
			id: new FormControl('', [Validators.required]),
			username: new FormControl('', [Validators.required, Validators.minLength(8)]),
			email: new FormControl('', [Validators.required, Validators.email]),
			password: new FormControl('', []),
		})

		this.spinnerService.show()
		this.userService.getUser(this.params.id).subscribe((response) => {
			this.spinnerService.hide()
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.pusername = obj.data.username
				this.userForm = this.formBuilder.group({
					id: [obj.data.id, Validators.required],
					username: [obj.data.username, Validators.required],
					email: [obj.data.email, [Validators.required, Validators.email]],
					password: ['', []],
				})
			}
		})

		this.getTransactions()
		this.getTeamMembers()
		this.getUserReferralIncome()
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

	editUser(data) {
		if (this.userForm.valid) {
			this.spinnerService.show()
			this.userService.editUser(this.params.id, data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.userFormResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					setTimeout((router: Router) => {
						this.userFormResponseText = ''
						this.router.navigate(['admin/users'])
					}, 3000)
				}
			})
		} else {
			this.validateAllFormFields(this.userForm)
		}
	}

	getTransactions() {
		this.userService.getTransactions({ user_id: this.params.id }).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.transactions = obj.data
		})
	}

	getUser() {
		this.userService.getUser(this.params.id).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.referral = obj.data.referral
		})
	}

	getTeamMembers() {
		this.userService.getUser(this.params.id).subscribe((response) => {
			this.spinnerService.hide()
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.pusername = obj.data.username
				this.userService.getTeamMembers(this.pusername).subscribe((response) => {
					let obj: any = JSON.parse(response._body)
					if (response.status == Config.SUCCESS_CODE) {
						this.level1 = obj.data.level1
						this.level2 = obj.data.level2
						this.level3 = obj.data.level3
						this.teamMembers = this.teamMembers.concat(this.level1)
						this.teamMembers = this.teamMembers.concat(this.level2)
						this.teamMembers = this.teamMembers.concat(this.level3)
						this.totalTeamMembers = this.teamMembers.length
					}
				})
			}
		})
	}

	getUserReferralIncome() {
		this.userService.getUserReferralIncome(this.params.id).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.referral_status = true
			if (response.status == Config.SUCCESS_CODE) {
				this.referral_income = obj.data
				this.totalTeamPurchase = parseInt(this.referral_income.level1.amount) + parseInt(this.referral_income.level2.amount) + parseInt(this.referral_income.level3.amount)
				this.totalTeamIncome = parseInt(this.referral_income.level1.referral_income) + parseInt(this.referral_income.level2.referral_income) + parseInt(this.referral_income.level3.referral_income)
			}
		})
	}
}
