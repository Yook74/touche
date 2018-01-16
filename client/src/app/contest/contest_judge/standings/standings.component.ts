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
        { header: 'Rank', dataField: 'rank', displayIsComponent: false, componentName: null },
        { header: 'Team', dataField: 'teamName', displayIsComponent: false, componentName: null },
        { header: 'Problems', dataField: 'problemsCompleted', displayIsComponent: true, componentName: JudgeStandingProblemsComponent },
        { header: 'Final Score', dataField: 'rank', displayIsComponent: true, componentName: JudgeStandingFinalScoreComponent }
    ];
    standings: Standing[];

    constructor(private service: StandingService) { }

    ngOnInit() {
        this.standings = this.service.getMockData();
    }
}
