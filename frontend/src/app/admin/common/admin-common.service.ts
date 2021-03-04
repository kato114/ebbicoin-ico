import { Injectable } from '@angular/core'
import { Http, Response, Headers, RequestOptions } from '@angular/http'
import { Observable } from 'rxjs/Rx'
import 'rxjs/add/operator/map'
import { Config } from './../../config/config'

@Injectable()
export class AdminCommonService {
	private url = Config.API + 'admin/login/'
	private userUrl = Config.API + 'admin/users/'
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

	logout(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'logout', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	transaction(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.userUrl + 'transaction', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	balance() {
		let options = { headers: this._header }
		return this.http
			.get(this.userUrl + 'balance')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	statics() {
		let options = { headers: this._header }
		return this.http
			.get(this.userUrl + 'statics')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	changePassword(data) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'changePassword', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	setOption(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.userUrl + 'setOption', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getOption() {
		let options = { headers: this._header }
		return this.http
			.post(this.userUrl + 'getOption', null, options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}
}
