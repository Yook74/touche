import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

const baseURL: string = 'http://localhost:8000';
const httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable()
export class BaseService {
    constructor(private http: HttpClient) { }

    get(route: string) {
        return this.http.get(`${baseURL}${route}.php`);
    }

    post(route: string, data) {
        return this.http.post(`${baseURL}${route}.php`, data, httpOptions);
    }

    put(route: string, data) {
        return this.http.put(`${baseURL}${route}.php`, data, httpOptions);
    }

    delete(route: string) {
        return this.http.delete(`${baseURL}${route}.php`);
    }
}