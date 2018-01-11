import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';
import 'rxjs/add/operator/filter';

const baseURL: string = 'http://localhost:8000';
const httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable()
export class BaseService {
    private params;

    constructor(private http: HttpClient, private router: Router, private route: ActivatedRoute) {
        this.router.events
            .filter(event => event instanceof NavigationEnd)
            .subscribe((event) => {
                let r = this.route;
                while (r.firstChild) {
                    r = r.firstChild
                }
                r.paramMap.subscribe(paramMap => {
                    this.params = paramMap['params'];
                });
            });
    }

    get(route: string) {
        return this.http.get(`${baseURL}/${this.params.contestName}/${route}.php`);
    }

    post(route: string, data) {
        return this.http.post(`${baseURL}/${this.params.contestName}/${route}.php`, data, httpOptions);
    }

    put(route: string, data) {
        return this.http.put(`${baseURL}/${this.params.contestName}/${route}.php`, data, httpOptions);
    }

    delete(route: string) {
        return this.http.delete(`${baseURL}/${this.params.contestName}/${route}.php`);
    }
}