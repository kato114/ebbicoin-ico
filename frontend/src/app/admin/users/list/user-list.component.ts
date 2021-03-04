import { Component, AfterViewInit } from '@angular/core'
import { NgClass } from '@angular/common'
import { Router } from '@angular/router'
import { AdminUsersService } from './../users.service'
import { Config } from './../../../config/config'
import { FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
declare var jQuery: any

@Component({
	selector: 'my-app',
	templateUrl: './user-list.component.html',
	styleUrls: ['./user-list.component.css'],
})
export class UsersListComponent implements AfterViewInit {
	public users: Object
	public message: String
	public ebbiCoinForm: FormGroup
	public term: any
	public s_field: String
	public s_dir: number

	constructor(private router: Router, public userService: AdminUsersService, private formBuilder: FormBuilder, private spinnerService: Ng4LoadingSpinnerService) {}

	ngOnInit() {
		this.ebbiCoinForm = this.formBuilder.group({
			coin: [null, Validators.required],
			user_id: [null, Validators.required],
		})

		this.getUser()
	}

	ngAfterViewInit() {
		console.log('as')
	}

	getUser() {
		this.s_field = this.s_field == undefined ? 'created_at' : this.s_field
		this.s_dir = this.s_dir == undefined ? 0 : this.s_dir

		this.userService.getUsers(this.s_field, this.s_dir).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.users = obj.data
			}
		})
	}

	changeStatus(id: Number, status: Number) {
		let data = { id: id, status: status }
		this.userService.changeStatus(data).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.message = obj.message
			setTimeout((router: Router) => {
				this.message = ''
			}, 3000)
			if (obj.status == Config.SUCCESS_CODE) {
				this.getUser()
				this.ngAfterViewInit()
			}
		})
	}

	showBalanceModal(user_id: Number) {
		this.ebbiCoinForm = this.formBuilder.group({
			coin: [null, Validators.required],
			user_id: [user_id, Validators.required],
		})

		jQuery('#BalanceModal').modal('show')
	}

	hideBalanceModal() {
		jQuery('#BalanceModal').modal('hide')
	}

	addUserEbbiCoinBalance(data) {
		console.log(data)

		if (this.ebbiCoinForm.valid) {
			this.spinnerService.show()

			this.userService.addUserEbbiCoinBalance(data).subscribe((response) => {
				this.spinnerService.hide()

				jQuery('#BalanceModal').modal('hide')

				let obj: any = JSON.parse(response._body)
				this.message = obj.message

				setTimeout((router: Router) => {
					this.message = ''
				}, 3000)

				if (obj.status == Config.SUCCESS_CODE) {
					this.ebbiCoinForm.reset()

					this.getUser()
					this.ngAfterViewInit()
				}
			})
		} else {
			this.validateAllFormFields(this.ebbiCoinForm)
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

	isFieldValid(field: string, form: any) {
		return !form.get(field).valid && form.get(field).touched
	}

	displayFieldCss(field: string, form: any) {
		return {
			'has-error': this.isFieldValid(field, form),
			'has-feedback': this.isFieldValid(field, form),
		}
	}

	sortNumberColumn(field: string) {
		if (this.s_field == field) this.s_dir = (this.s_dir + 1) % 2
		else this.s_field = field

		this.userService.getUsers(this.s_field, this.s_dir).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.users = obj.data
			}
		})
	}
}
