import { Injectable } from '@angular/core';
import { BaseService } from './model_services/base.service';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class AuthenticationService {
    constructor(private baseService: BaseService, private cookieService: CookieService) { }

    teamLogin(username: string, password: string) {
        return this.baseService.post('login', { username: username, password: password });
    }

    judgeLogin(username: string, password: string) {
        return this.baseService.post('judge/login', { username: username, password: password });
    }

    adminLogin(username: string, password: string) {
        return this.baseService.post('admin/login', { username: username, password: password });
    }

    createContestLogin(username: string, password: string) {

    }

    teamIsAuthenticated(): boolean {
        return this.cookieService.check('Team-Token');
    }

    judgeIsAuthenticated(): boolean {
        return this.cookieService.check('Judge-Token');
    }

    adminIsAuthenticated(): boolean {
        return this.cookieService.check('Admin-Token');
    }
}