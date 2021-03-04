import { Injectable } from '@angular/core'
import { Http, Response, Headers, RequestOptions } from '@angular/http'
import { Observable } from 'rxjs/Rx'
import 'rxjs/add/operator/map'
import { Config } from './../config/config'

@Injectable()
export class HomeService {
	private url = Config.API + 'user/'
	private home = Config.API + 'home/'
	private _header: Headers = new Headers()

	constructor(private http: Http) {
		this._header.append('Content-Type', 'application/json')
	}

	getData() {
		let options = { headers: this._header }
		return this.http
			.get(this.home + 'getData')
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	register(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'register', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	login(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'login', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	frgtPwd(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'forgetPassword', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	resendEmail(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'resendEmail', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	haveQuestion(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'haveQuestion', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	community(data: any) {
		let options = { headers: this._header }
		return this.http
			.post('https://www.aweber.com/scripts/addlead.pl', JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}
}
