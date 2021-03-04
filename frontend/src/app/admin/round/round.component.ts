import { Component, AfterViewInit } from '@angular/core'
import { Router, ActivatedRoute } from '@angular/router'
import { AdminRoundService } from './round.service'
import { Config } from './../../config/config'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
declare var jQuery: any

@Component({
	selector: 'my-app',
	templateUrl: './round.component.html',
})
export class AdminRoundComponent {
	public supportForm: any
	public supportResponseText: any
	public term: any
	public round: any
	public rounds: any
	private params: any

	constructor(private router: Router, private roundService: AdminRoundService, private routeParams: ActivatedRoute, private spinnerService: Ng4LoadingSpinnerService) {
		this.router.routeReuseStrategy.shouldReuseRoute = () => false
	}

	ngOnInit() {
		this.routeParams.params.subscribe((params) => {
			this.params = params
		})

		console.log(this.params)

		this.getRound()
	}

	getRound() {
		this.roundService.getStageTransactions(this.params.stage).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.rounds = obj.data
			}
		})
	}
}
