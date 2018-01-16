import { Injectable } from '@angular/core';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';

@Injectable()
export class ContestNameService {
    private params;

    constructor(private router: Router, private route: ActivatedRoute) {
        this.router.events
            .filter(event => event instanceof NavigationEnd)
            .subscribe((event) => {
                let r = this.route;
                while (r.firstChild && !r.snapshot.params.contestName) {
                    r = r.firstChild
                }
                this.params = r.snapshot.params;
            });
    }

    getContestName() {
        return this.params.contestName;
    }
}