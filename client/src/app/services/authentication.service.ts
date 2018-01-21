import { Injectable } from '@angular/core';
import { BaseService } from './model_services/base.service';

@Injectable()
export class AuthenticationService {
    constructor(private baseService: BaseService) { }

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
}