import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ContestNameService } from '../services/contest_name.service';

@Component({
    templateUrl: './contest.component.html',
    styleUrls: ['./contest.component.css']
})
export class ContestComponent implements OnInit {
    opened: boolean;
    routeName: string;
    contestName: string;

    constructor(private route: ActivatedRoute, private contestNameService: ContestNameService) {
        this.opened = true;
    }

    ngOnInit() {
        this.routeName = this.route.firstChild.snapshot.data['route'];
        this.contestName = this.contestNameService.getContestName();
    }

    toggleMenu() {
        this.opened = !this.opened;
    }
}
