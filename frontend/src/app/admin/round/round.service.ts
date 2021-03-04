import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import { Config } from './../../config/config';

@Injectable()
export class AdminRoundService {

    private url=Config.API + 'admin/transaction/';
    private _header: Headers = new Headers();
    private _options: Object;

    constructor( 
        private http: Http
    ) {
        this._header.append('Content-Type', 'application/json');
        this._options = { headers: this._header };
    }

    getStageTransactions( stage ) {
        return this.http.get(this.url + "getStageTransactions/" + stage )
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

}