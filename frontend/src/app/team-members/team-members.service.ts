import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import { Config } from './../config/config';

@Injectable()
export class TeamMembersService {

    private url=Config.API+'user/';
    private transactionUrl=Config.API+'transaction/';
    private coinbaseUrl=Config.API+'coinbase/';
    private _header: Headers = new Headers();
    private _options: Object;

    constructor( 
        private http: Http
    ) {
        this._header.append('Content-Type', 'application/json');
        this._options = { headers: this._header };
    }

    getTeamMembers( user_id: any ) {
        return this.http.get(this.url + "getTeamMembers/" + user_id)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    getUserReferralIncome( user_id: any ) {
        return this.http.get(this.url + "getUserReferralIncome/" + user_id)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    getUser( user_id: any ) {
        return this.http.get(this.url + "getUser/" + user_id)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

}