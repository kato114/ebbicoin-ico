import { Injectable } from '@angular/core'
import { Http, Response, Headers, RequestOptions } from '@angular/http'
import { Observable } from 'rxjs/Rx'
import 'rxjs/add/operator/map'
import { Config } from './../../config/config'

@Injectable()
export class AdminUsersService {
	private url = Config.API + 'admin/users/'
	private uurl = Config.API + 'user/'
	private transactionUrl = Config.API + 'transaction/'
	private _header: Headers = new Headers()
	private options: any

	constructor(private http: Http) {
		this._header.append('Content-Type', 'application/json')
		this.options = { headers: this._header }
	}

	getUsers(field: String, dir: number) {
		return this.http
			.get(this.url + 'getUsers/' + field + '/' + dir)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getUser(id: Number) {
		return this.http
			.get(this.url + 'getUser/' + id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	addUser(data: any) {
		return this.http
			.post(this.url + 'add', JSON.stringify(data), this.options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	changeStatus(data: any) {
		return this.http
			.post(this.url + 'changeStatus', JSON.stringify(data), this.options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	editUser(id: Number, data: any) {
		return this.http
			.post(this.url + 'edit', JSON.stringify(data), this.options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	addUserEbbiCoinBalance(data: any) {
		return this.http
			.post(this.url + 'addUserEbbiCoinBalance', JSON.stringify(data), this.options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getTransactions(data: any) {
		return this.http
			.post(this.transactionUrl + '/', JSON.stringify(data), this.options)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getTeamMembers(user_id: any) {
		return this.http
			.get(this.uurl + 'getTeamMembers/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}

	getUserReferralIncome(user_id: any) {
		return this.http
			.get(this.uurl + 'getUserReferralIncome/' + user_id)
			.map((response: Response) => response)
			.catch((error: any) => Observable.throw(error.json().error || 'Server error'))
	}
}
