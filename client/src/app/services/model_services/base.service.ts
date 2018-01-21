import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ContestNameService } from '../contest_name.service';
import { CookieService } from 'ngx-cookie-service';
import * as data from '../../../assets/config.json';

const baseURL: string = `${data['serverUrl']}/~contest_skeleton`;
const httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable()
export class BaseService {

    constructor(private http: HttpClient, private contestNameService: ContestNameService, private cookieService: CookieService) {
        httpOptions.headers.append('Authorization', this.cookieService.get('Token'));
    }

    get(route: string) {
        return this.http.get(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`, httpOptions);
    }

    post(route: string, data) {
        return this.http.post(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`, data, httpOptions);
    }

    put(route: string, data) {
        return this.http.put(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`, data, httpOptions);
    }

    delete(route: string) {
        return this.http.delete(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`, httpOptions);
    }
}