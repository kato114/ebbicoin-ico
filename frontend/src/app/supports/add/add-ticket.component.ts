import { Component } from '@angular/core'
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { Router } from '@angular/router'
import { SupportService } from './../support.service'
import { Config } from './../../config/config'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
declare var jQuery: any

@Component({
	selector: 'my-app',
	templateUrl: './add-ticket.component.html',
})
export class SupportsAddComponent {
	public supportForm: any
	public supportResponseText: any

	constructor(private router: Router, private supportService: SupportService, private spinnerService: Ng4LoadingSpinnerService) {}

	ngOnInit() {
		this.supportForm = new FormGroup({
			user_id: new FormControl(localStorage.getItem('user_id'), [Validators.required]),
			title: new FormControl('', [Validators.required]),
			description: new FormControl('', [Validators.required]),
			image: new FormControl('', []),
		})
	}

	addNewTicket(data) {
		if (this.supportForm.valid) {
			this.spinnerService.show()
			this.supportService.addTicket(data).subscribe((response) => {
				this.spinnerService.hide()
				let obj: any = JSON.parse(response._body)
				this.supportResponseText = obj.message
				if (obj.status == Config.SUCCESS_CODE) {
					this.supportForm.reset()
					this.router.navigate(['supports'])
				}
			})
		} else {
			this.validateAllFormFields(this.supportForm)
		}
	}

	onFileChange(event) {
		let reader = new FileReader()
		if (event.target.files && event.target.files.length > 0) {
			let file = event.target.files[0]
			reader.readAsDataURL(file)
			reader.onload = () => {
				this.supportForm.get('image').setValue({
					filename: file.name,
					filetype: file.type,
					value: reader.result.split(',')[1],
				})
			}
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
