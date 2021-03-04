import { Injectable } from '@angular/core'
import { Http, Response, Headers, RequestOptions } from '@angular/http'
import { Observable } from 'rxjs/Rx'
import 'rxjs/add/operator/map'
import { Config } from './../config/config'

@Injectable()
export class MyWalletsService {
	private url = Config.API + 'user/'
	private _header: Headers = new Headers()

	constructor(private http: Http) {
		this._header.append('Content-Type', 'application/json')
	}

	transfer(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'transfer/' + localStorage.getItem('user_id'), JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	transferEther(data: any) {
		let options = { headers: this._header }
		return this.http
			.post(this.url + 'transferEther/' + localStorage.getItem('user_id'), JSON.stringify(data), options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}
}
