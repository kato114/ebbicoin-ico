import { Component } from '@angular/core'
import { AdminCommonService } from '../common/admin-common.service'
import { FormBuilder, Validators, FormGroup, FormControl } from '@angular/forms'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
import { Router } from '@angular/router'
import { Config } from '../../config/config'
declare var jQuery: any

@Component({
	selector: 'my-app',
	templateUrl: './dashboard.component.html',
	styleUrls: ['./dashboard.component.css'],
})
export class AdminDashboardComponent {
	public transactionForm: any
	public stageForm: any
	public responseText: any
	public stageText: any
	public eth_balance: any
	public accounts: any
	public active_accounts: any
	public ebbicoins: any
	public eth_transfered: any

	constructor(private router: Router, private formBuilder: FormBuilder, private commonService: AdminCommonService, private spinnerService: Ng4LoadingSpinnerService) {}

	ngOnInit() {
		this.transactionForm = this.formBuilder.group({
			address: [null, Validators.required],
			amount: [null, Validators.required],
		})

		this.stageForm = this.formBuilder.group({
			stage: [null, Validators.required],
			price: [null, Validators.required],
		})

		this.commonService.balance().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.eth_balance = obj.data
		})

		this.commonService.statics().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.accounts = obj.data.accounts
			this.active_accounts = obj.data.active_accounts
			this.ebbicoins = obj.data.ebbicoins
			this.eth_transfered = obj.data.eth_transfered
		})

		this.commonService.getOption().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.stageForm = this.formBuilder.group({
					stage: [obj.data.stage, Validators.required],
					price: [obj.data.price, [Validators.required]],
				})
			}
		})
	}

	isFieldValid(field: string, form: any) {
		return !form.get(field).valid && form.get(field).touched
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

	transactionSubmit(data) {
		if (this.transactionForm.valid) {
			this.spinnerService.show()

			this.commonService.transaction(data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.responseText = '<p class="alert alert-' + obj.class + '">' + obj.message + '</p>'

				if (obj.status == Config.SUCCESS_CODE) {
					this.transactionForm.reset()
				}
			})
		} else {
			this.validateAllFormFields(this.transactionForm)
		}
	}

	stageSubmit(data) {
		if (this.stageForm.valid) {
			this.spinnerService.show()

			this.commonService.setOption(data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.stageText = '<p class="alert alert-' + obj.class + '">' + obj.message + '</p>'
			})
		} else {
			this.validateAllFormFields(this.stageForm)
		}
	}
}
