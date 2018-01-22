import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from '../../../services/authentication.service';
import { CookieService } from 'ngx-cookie-service';
import { ContestNameService } from '../../../services/contest_name.service';
import { Router } from '@angular/router';

@Component({
    templateUrl: 'login.component.html'
})
export class ContestLiveLogin implements OnInit {
    username: string = '';
    password: string = '';
    contestName: string;
    loginFailed: boolean = false;

    constructor(
        private authenticationService: AuthenticationService,
        private cookieService: CookieService,
        private contestNameService: ContestNameService,
        private router: Router
    ) {
        this.contestName = this.contestNameService.getContestName();
    }

    ngOnInit() {

    }

    login() {
        this.loginFailed = false;
        this.authenticationService.teamLogin(this.username, this.password).subscribe((jwt: string) => {
            this.cookieService.set('Token', jwt);
            this.cookieService.set('Team-Token', jwt);
            this.router.navigate(['contest', this.contestName]);
        }, () => {
            this.loginFailed = true;
        });
    }
}
