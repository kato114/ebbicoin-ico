import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import { Config } from './../config/config';

@Injectable()
export class SupportService {

    private url=Config.API + 'support/';
    private _header: Headers = new Headers();
    private _options: Object;

    constructor( 
        private http: Http
    ) {
        this._header.append('Content-Type', 'application/json');
        this._options = { headers: this._header };
    }

    getTickets( user_id: any ) {
        return this.http.get(this.url + "index/" + user_id)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    getTicket( ticket_id: any ) {
        return this.http.get(this.url + "view/" + ticket_id)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    addTicket( data: any ) {
        return this.http.post(this.url + "add/", JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

    addComment( data: any ) {
        return this.http.post(this.url + "addComment/", JSON.stringify(data), this._options)
        .map((response:Response) => response)
        .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }

}