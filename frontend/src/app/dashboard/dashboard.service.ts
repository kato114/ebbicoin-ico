import { Injectable } from '@angular/core'
import { Http, Response, Headers, RequestOptions } from '@angular/http'
import { Observable } from 'rxjs/Rx'
import 'rxjs/add/operator/map'
import { Config } from './../config/config'

@Injectable()
export class DashboardService {
	private url = Config.API + 'user/'
	private home = Config.API + 'home/'
	private transactionUrl = Config.API + 'transaction/'
	private coinbaseUrl = Config.API + 'coinbase/'
	private _header: Headers = new Headers()
	private _options: Object

	constructor(private http: Http) {
		this._header.append('Content-Type', 'application/json')
		this._options = { headers: this._header }
	}

	getUser(user_id: any) {
		return this.http
			.get(this.url + 'getUser/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getUserAccount(user_id: any, currency: any) {
		return this.http
			.get(this.url + 'getUserAccount/' + user_id + '/' + currency)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getRate() {
		return this.http
			.get('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin%2Cethereum%2Cbitcash&vs_currencies=usd')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getGasPrices() {
		return this.http
			.get('https://ethgasstation.info/json/ethgasAPI.json')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getCoinPrice() {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'getOption', null, options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	buyEbbiCoin(data: any) {
		return this.http
			.post(this.transactionUrl + 'add', JSON.stringify(data), this._options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	buyEbbiCoinCryptoTransfer(data: any) {
		return this.http
			.post(this.coinbaseUrl + 'wallet_transactions_send', JSON.stringify(data), this._options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	buyCoin(data: any) {
		return this.http
			.get(this.coinbaseUrl + 'wallet_buys_create?' + data)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getTransactions(data: any) {
		return this.http
			.post(this.transactionUrl + '/', JSON.stringify(data), this._options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getUserBalance(data: any) {
		return this.http
			.post(this.url + 'balance/', JSON.stringify(data), this._options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getPaymentMethods(data: any) {
		return this.http
			.get(this.coinbaseUrl + 'wallet_payment_methods_read?user_id=' + data)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	check() {
		return this.http
			.get('https://ebbicoin.com/data/index.php/user/check')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	updateBalance(user_id) {
		return this.http
			.get(this.url + 'updateBalance/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getSoldCoins() {
		return this.http
			.get(this.home + 'getSoldCoins')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	setTime(user_id: any) {
		return this.http
			.get(this.home + 'setTime/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getTime(user_id: any) {
		return this.http
			.get(this.home + 'getTime/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getCurrentStage() {
		return this.http
			.get(this.home + 'getData')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	createWallet(user_id: any) {
		return this.http
			.get(this.url + 'createWallet/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}
}
