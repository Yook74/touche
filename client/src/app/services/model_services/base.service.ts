import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ContestNameService } from '../contest_name.service';

const baseURL: string = 'http://localhost:8000/~contest-skeleton';
const httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable()
export class BaseService {

    constructor(private http: HttpClient, private contestNameService: ContestNameService) { }

    get(route: string) {
        return this.http.get(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`);
    }

    post(route: string, data) {
        return this.http.post(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`, data, httpOptions);
    }

    put(route: string, data) {
        return this.http.put(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`, data, httpOptions);
    }

    delete(route: string) {
        return this.http.delete(`${baseURL}/${this.contestNameService.getContestName()}/${route}.php`);
    }
}