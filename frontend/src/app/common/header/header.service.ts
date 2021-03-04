import { Injectable } from '@angular/core'
import { Http, Response, Headers, RequestOptions } from '@angular/http'
import { Observable } from 'rxjs/Rx'
import 'rxjs/add/operator/map'
import { Config } from './../../config/config'

@Injectable()
export class HeaderService {
	private url = Config.API + 'user/'
	private _header: Headers = new Headers()

	constructor(private http: Http) {
		this._header.append('Content-Type', 'application/json')
	}

	loginStatus(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'loginStatus', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	loginStatus2(token, user_id) {
		return this.http.get(this.url + 'loginStatus').map((response) => response.json())
	}

	getRate() {
		return this.http
			.get('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin%2Cethereum%2Cbitcash&vs_currencies=usd')
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
}
