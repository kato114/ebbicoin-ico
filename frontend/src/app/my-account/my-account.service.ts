import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import { Config } from './../config/config';

@Injectable()
export class MyAccountService {

    private url=Config.API+'user/';
    private transactionUrl=Config.API+'transaction/';
    private _header: Headers = new Headers();
    private _options: Object;

    constructor( 
        private http: Http
    ) {
        this._header.append('Content-Type', 'application/json');
        this._options = { headers: this._header };
    }

    getUser( user_id: any ) {
        return this.http.get(this.url + "getUser/" + user_id)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    updateAccount( user_id: any, data: any ) {
        return this.http.post(this.url + "edit/" + user_id, JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    twoFactorAuthQRCode( username: any, tfa_key: any ) {
        return this.http.get(this.url + "twoFactorAuthQRCode/" + username + "/" + tfa_key)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    enable2FA( user_id: any, data: any ) {
        return this.http.post(this.url + "enable2FA/" + user_id, JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    disable2FA( user_id: any, data: any ) {
        return this.http.post(this.url + "disable2FA/" + user_id, JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    deactivate2FA( user_id: any, data: any ) {
        return this.http.post(this.url + "deactivate2FA/" + user_id, JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    changePassword( user_id: any, data: any ) {
        return this.http.post(this.url + "changePassword/" + user_id, JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

}