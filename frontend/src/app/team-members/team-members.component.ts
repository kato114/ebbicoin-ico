import { Component } from '@angular/core'
import { TeamMembersService } from './team-members.service'
import { Config } from '../config/config'

@Component({
	selector: 'my-app',
	templateUrl: './team-members.component.html',
})
export class TeamMembersComponent {
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
	public username: any

	constructor(private teamService: TeamMembersService) {
		this.username = localStorage.getItem('username')
	}

	ngOnInit() {
		this.getUser()
		this.getTeamMembers()
		this.getUserReferralIncome()
	}

	getUser() {
		this.teamService.getUser(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.referral = obj.data.referral
		})
	}

	getTeamMembers() {
		this.teamService.getTeamMembers(localStorage.getItem('username')).subscribe((response) => {
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

	getUserReferralIncome() {
		this.teamService.getUserReferralIncome(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.referral_status = true
			if (response.status == Config.SUCCESS_CODE) {
				this.referral_income = obj.data
				this.totalTeamPurchase = parseInt(this.referral_income.level1.amount) + parseInt(this.referral_income.level2.amount) + parseInt(this.referral_income.level3.amount)
				this.totalTeamIncome = parseInt(this.referral_income.level1.referral_income) + parseInt(this.referral_income.level2.referral_income) + parseInt(this.referral_income.level3.referral_income)
			}
		})
	}

	copyReferralLink() {
		let temp = jQuery('<input>')
		$('body').append(temp)
		temp.val($('#btcAddressCopy').val()).select()
		document.execCommand('copy')
		temp.remove()
		alert('Your referral link copied to clipboard!')
	}
}
