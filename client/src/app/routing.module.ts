import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { CreateContestComponent } from './create_contest/create_contest.component';
import { ContestComponent } from './contest/contest.component';
import { ContestLiveComponent } from './contest/contest_live/contest_live.component';
import { LiveSideNavComponent } from './contest/contest_live/sidenav/sidenav.component';
import { LiveContestDetailComponent } from './contest/contest_live/contest_detail/contest_detail.component';
import { LiveClarificationsComponent } from './contest/contest_live/clarifications/clarifications.component';
import { LiveProblemsComponent } from './contest/contest_live/problems/problems.component';
import { LiveStandingsComponent } from './contest/contest_live/standings/standings.component';
import { ContestJudgeComponent } from './contest/contest_judge/contest_judge.component';
import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';

const appRoutes: Routes = [
    { path: 'create-contest', component: CreateContestComponent },
    {
        path: 'contest/:contestName', component: ContestComponent, children: [
            { path: 'judge', component: ContestJudgeComponent, data: { route: 'judge' } },
            { path: 'admin', component: ContestAdminComponent, data: { route: 'admin' } },
            {
                path: '', component: ContestLiveComponent, data: { route: 'live' }, children: [
                    { path: 'contest-detail', component: LiveContestDetailComponent },
                    { path: 'clarifications', component: LiveClarificationsComponent },
                    { path: 'problems', component: LiveProblemsComponent },
                    { path: 'standings', component: LiveStandingsComponent },
                    { path: '', redirectTo: 'contest-detail', pathMatch: 'full' }
                ]
            },
        ]
    },
    { path: '', redirectTo: '/create-contest', pathMatch: 'full' }
];

@NgModule({
    imports: [
        RouterModule.forRoot(
            appRoutes
        )
    ],
    exports: [
        RouterModule
    ]
})
export class RoutingModule { }