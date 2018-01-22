import { Component, OnInit } from '@angular/core';
import { ContestInfo } from '../../../models/contest_info';
import { ContestInfoService } from '../../../services/model_services/contest_info.service';

@Component({
    templateUrl: './contest_detail.component.html'
})
export class AdminContestDetailComponent implements OnInit {

    contest: ContestInfo;

    constructor(private service: ContestInfoService) { }

    ngOnInit() {
        this.contest = this.service.getMockData();
    }
}
