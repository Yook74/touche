import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { CreateContestComponent } from './create_contest/create_contest.component';
import { ContestLiveComponent } from './contest/contest_live/contest_live.component';
import { ContestJudgeComponent } from './contest/contest_judge/contest_judge.component';
import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';

const appRoutes: Routes = [
    { path: 'create-contest', component: CreateContestComponent },
    {
        path: 'contest/:contestName', children: [
            { path: 'judge', component: ContestJudgeComponent },
            { path: 'admin', component: ContestAdminComponent },
            { path: '', component: ContestLiveComponent }
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