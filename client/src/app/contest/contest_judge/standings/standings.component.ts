import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Standing } from '../../../models/standing';
import { StandingService } from '../../../services/model_services/standing.service';
import { JudgeStandingProblemsComponent } from './problems/problems.component';
import { JudgeStandingFinalScoreComponent } from './final_score/final_score.component';

@Component({
    templateUrl: './standings.component.html'
})
export class JudgeStandingsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Rank', dataField: 'rank', displayIsComponent: false, component: null },
        { header: 'Team', dataField: 'teamName', displayIsComponent: false, component: null },
        { header: 'Problems', dataField: 'problemsCompleted', displayIsComponent: true, component: JudgeStandingProblemsComponent },
        { header: 'Final Score', dataField: 'rank', displayIsComponent: true, component: JudgeStandingFinalScoreComponent }
    ];
    standings: Standing[];

    constructor(private service: StandingService) { }

    ngOnInit() {
        this.standings = this.service.getMockData();
    }
}
