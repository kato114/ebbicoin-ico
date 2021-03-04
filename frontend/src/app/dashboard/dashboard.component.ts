import { Component } from '@angular/core'
import { AbstractControl, FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms'
import { Router } from '@angular/router'
import { DashboardService } from './dashboard.service'
import { Config } from './../config/config'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner'
import { DomSanitizer, SafeResourceUrl, SafeUrl } from '@angular/platform-browser'
declare var jQuery: any
import { Cookie } from 'ng2-cookies/ng2-cookies'

@Component({
	selector: 'my-app',
	templateUrl: './dashboard.component.html',
	styleUrls: ['./dashboard.component.css'],
})
export class DashboardComponent {
	public username: any
	public user_id: any
	public balance: any = { btc_balance: 'loading...', eth_balance: 'loading...', bch_balance: 'loading...', ebbi_balance: 'loading...' }
	public buyEbbiCoin: FormGroup
	public buyCoin: FormGroup
	public rate: any = { btc: 0, eth: 0, bch: 0, ebbi: 0.7 }
	public transactions: any
	public ethIframe: any
	public user: any = {
		eth_address: '',
	}
	public url: any = Config.API
	public paymentMethods: any

	public EthEbbiMessage: any
	public CoinMessage: any

	public checkIframe: SafeResourceUrl

	public updateBalanceResponse: any

	public soldCoins: any = 0
	public stage: any
	public totalCoins: any = 24000000

	public accounts: any
	public isConnect: any = false
	public gasPrices: any

	public ethereum: any

	constructor(private router: Router, private dashboardService: DashboardService, private spinnerService: Ng4LoadingSpinnerService, private sanitizer: DomSanitizer) {
		this.dashboardService.getRate().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.rate.btc = parseFloat(obj.bitcoin.usd)
			this.rate.eth = parseFloat(obj.ethereum.usd)
			this.rate.bch = parseFloat(obj.bitcash.usd)

			localStorage.setItem('eth_rate', this.rate.eth)
		})

		this.dashboardService.getCoinPrice().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.rate.ebbi = parseFloat(obj.data.price)
		})

		this.getTransactions()
		this.getUserBalance()
		this.getUserDetail()
		this.getUserAccountDetail('ETH')
		this.getSoldCoins()
		this.showMsg()
		this.getCurrentStage()
	}

	ngOnInit() {
		this.username = localStorage.getItem('username')
		this.user_id = localStorage.getItem('user_id')

		this.buyEbbiCoin = new FormGroup({
			eth: new FormControl('', [Validators.required]),
			ebbi: new FormControl('', [Validators.required]),
		})

		this.buyCoin = new FormGroup({
			currency: new FormControl('', [Validators.required]),
			quantity: new FormControl('', [Validators.required]),
			usd: new FormControl('', [Validators.required]),
			payment_method: new FormControl('', [Validators.required]),
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

	showExchangeModal() {
		jQuery('#AddressModal').modal('show')
		// jQuery('#ExchangeModal').modal('show');
	}

	hideExchangeModal() {
		jQuery('#ExchangeModal').modal('hide')
	}

	showAddressModal() {
		jQuery('#ExchangeModal').modal('hide')
		jQuery('#AddressModal').modal('show')
	}

	hideAddressModal() {
		jQuery('#AddressModal').modal('hide')
	}

	showBuyEbbiCoinModal() {
		jQuery('#ExchangeModal').modal('hide')
		jQuery('#BuyEbbiCoinModal').modal('show')
	}

	hideBuyEbbiCoinModal() {
		this.EthEbbiMessage = ''
		jQuery('#BuyEbbiCoinModal').modal('hide')
	}

	showCoinBuyModal(currency: any) {
		this.getPaymentMethods()
		this.buyCoin.controls['currency'].setValue(currency)
		jQuery('#ExchangeModal').modal('hide')
		jQuery('#CoinBuyModal').modal('show')
	}

	hideCoinBuyModal() {
		this.buyCoin.reset()
		jQuery('#CoinBuyModal').modal('hide')
	}

	showQRModal() {
		jQuery('#AddressModal').modal('hide')
		jQuery('#QRModal').modal('show')
	}

	hideQRModal() {
		jQuery('#QRModal').modal('hide')
	}

	copyAddress() {
		let temp = jQuery('<input>')
		$('body').append(temp)
		temp.val($('#ETHAddress').val()).select()
		document.execCommand('copy')
		temp.remove()
		alert('Your ethereum address copied to clipboard!')
	}

	buyEbbiCoinSubmit(data) {
		if (this.buyEbbiCoin.valid) {
			this.EthEbbiMessage = '<p class="alert alert-danger">Under Construction</p>'
			/* this.spinnerService.show();
            let obj:any = {
                to                  : '0xa7169E9062E67f420565B524e9652e9ac4B9a7e4',
                user_id             : localStorage.getItem('user_id'),
                send_quantity       : data.eth,
                send_rate           : this.rate.eth,
                receive_quantity    : data.ebbi,
                receive_rate        : this.rate.ebbi,
                exchange            : 'ETH-EBBI',
                currency            : 'ETH'
            };
            this.dashboardService.buyEbbiCoinCryptoTransfer( obj )
            .subscribe(response => {
                let obj:any = JSON.parse(response._body);
                this.EthEbbiMessage = '<p class="alert alert-' + obj.class + '">' + obj.message + '</p>';
                if( obj.status === Config.SUCCESS_CODE ){
                    this.buyEbbiCoin.reset();
                    this.dashboardService.buyEbbiCoin( obj )
                    .subscribe(response => {
                        if( obj.status === Config.SUCCESS_CODE ){
                            this.getTransactions();
                            this.getUserBalance();
                        }
                    });
                }
                this.spinnerService.hide();
            }); */
		} else {
			this.validateAllFormFields(this.buyEbbiCoin)
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

	calculateEbbi(eth: any) {
		this.buyEbbiCoin.controls['ebbi'].setValue(((eth * this.rate.eth) / this.rate.ebbi).toFixed(8))
	}

	calculateEth(ebbi: any) {
		this.buyEbbiCoin.controls['eth'].setValue(((ebbi * this.rate.ebbi) / this.rate.eth).toFixed(8))
	}

	calculateCoin(quantity: any) {
		let currency = this.buyCoin.get('currency').value
		let rate = 0
		if (currency == 'ETH') {
			rate = this.rate.eth
		}
		this.buyCoin.controls['usd'].setValue((rate * quantity).toFixed(8))
	}

	calculateCoinUSD(usd: any) {
		let currency = this.buyCoin.get('currency').value
		let rate = 0
		if (currency == 'ETH') {
			rate = this.rate.eth
		}
		this.buyCoin.controls['quantity'].setValue((usd / rate).toFixed(8))
	}

	getTransactions() {
		this.dashboardService.getTransactions({ user_id: localStorage.getItem('user_id') }).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			this.transactions = obj.data
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
				this.user = obj.data
				this.ethIframe = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + this.user.eth_address
				this.ethIframe = this.sanitizer.bypassSecurityTrustResourceUrl(this.ethIframe)

				localStorage.setItem('eth_address', this.user.eth_address)
			}
		})
	}

	getUserAccountDetail(currency: any) {
		this.dashboardService.getUserAccount(localStorage.getItem('user_id'), currency).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
			}
		})
	}

	buyCoinSubmit(data) {
		if (this.buyCoin.valid) {
			this.spinnerService.show()

			let receive_rate = 0

			if (data.currency == 'ETH') {
				receive_rate = this.rate.eth
			}

			let params = 'user_id=' + localStorage.getItem('user_id') + '&send_quantity=' + data.usd + '&send_rate=' + 1 + '&receive_quantity=' + data.quantity + '&receive_rate=' + receive_rate + '&exchange=USD-' + data.currency + '&currency=' + data.currency + '&payment_method=' + data.payment_method
			this.dashboardService.buyCoin(params).subscribe((response) => {
				let obj: any = JSON.parse(response._body)
				this.CoinMessage = '<p class="alert alert-' + obj.class + '">' + obj.message + '</p>'
				if (obj.status === Config.SUCCESS_CODE) {
					this.buyCoin.reset()
					this.getTransactions()
					this.getUserBalance()
				}
				this.spinnerService.hide()
			})
		} else {
			this.validateAllFormFields(this.buyCoin)
		}
	}

	getPaymentMethods() {
		this.dashboardService.getPaymentMethods(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				this.paymentMethods = obj.data
			}
		})
	}

	updateBalance() {
		this.dashboardService.getTime(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				let time = obj.data

				if (time < 300) {
					let i = 300 - time

					jQuery('.btn-update').attr('disabled', true)

					let interval = setInterval(() => {
						this.updateBalanceResponse = '<p class="alert alert-warning">Please wait ' + i + ' seconds before clicking again</p>'
						i = i - 1
					}, 1000)

					setTimeout(() => {
						clearInterval(interval)
						this.updateBalanceResponse = ''
						jQuery('.btn-update').attr('disabled', false)
					}, (i + 2) * 1000)
				} else {
					this.spinnerService.show()
					this.dashboardService.updateBalance(localStorage.getItem('user_id')).subscribe((response) => {
						let obj: any = JSON.parse(response._body)
						this.getTransactions()
						this.getUserBalance()

						this.spinnerService.hide()

						if (obj.status == 400) {
							if (obj.message == 'Zero Balance') this.updateBalanceResponse = '<p class="alert alert-danger alert-text-white">\
								Whoops! There doesn\'t seem to be any Ethereum in your wallet right now. If you have sent Ethereum, \
								please wait for it to confirm on the blockchain.'
							else this.updateBalanceResponse = '<p class="alert alert-danger alert-text-white">\
								The transaction cannot be completed because your balance is not enough to cover the transfer fees. <br>\
								Please send more Ethereum in order to complete the transaction.'
						} else {
							this.dashboardService.setTime(localStorage.getItem('user_id')).subscribe()

							jQuery('.btn-update').attr('disabled', true)

							let i = 300
							let interval = setInterval(() => {
								this.updateBalanceResponse = '<p class="alert alert-warning">Please wait ' + i + ' seconds before clicking again</p>'
								i = i - 1
							}, 1000)

							setTimeout(() => {
								clearInterval(interval)
								this.updateBalanceResponse = ''
								jQuery('.btn-update').attr('disabled', false)
							}, 302000)
						}
					})
				}
			}
		})
	}

	getSoldCoins() {
		this.dashboardService.getSoldCoins().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.soldCoins = parseInt(obj.data.soldCoins)
				let bar = ((this.soldCoins / this.totalCoins) * 100).toFixed(2)
				jQuery('#data-token-sold').css('width', bar + '%')
			}
		})
	}

	getCurrentStage() {
		this.dashboardService.getCurrentStage().subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status == Config.SUCCESS_CODE) {
				this.stage = obj.data.stage
				console.log(this.stage)
			}
		})
	}

	showMsg() {
		this.dashboardService.getTime(localStorage.getItem('user_id')).subscribe((response) => {
			let obj: any = JSON.parse(response._body)
			if (obj.status === Config.SUCCESS_CODE) {
				let time = obj.data

				if (time < 300) {
					let i = 300 - time

					jQuery('.btn-update').attr('disabled', true)

					let interval = setInterval(() => {
						this.updateBalanceResponse = '<p class="alert alert-warning">Please wait ' + i + ' seconds before clicking again</p>'
						i = i - 1
					}, 1000)

					setTimeout(() => {
						clearInterval(interval)
						this.updateBalanceResponse = ''
						jQuery('.btn-update').attr('disabled', false)
					}, (i + 2) * 1000)
				}
			}
		})
	}
}
