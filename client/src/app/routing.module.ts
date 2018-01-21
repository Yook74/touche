import 'rxjs/add/operator/filter';
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
import { JudgeContestDetailComponent } from './contest/contest_judge/contest_detail/contest_detail.component';
import { JudgeSubmissionsComponent } from './contest/contest_judge/submissions/submissions.component';
import { JudgeClarificationsComponent } from './contest/contest_judge/clarifications/clarifications.component';
import { JudgeProblemsComponent } from './contest/contest_judge/problems/problems.component';
import { JudgeStandingsComponent } from './contest/contest_judge/standings/standings.component';
import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';
import { AdminContestDetailComponent } from './contest/contest_admin/contest_detail/contest_detail.component';
import { AdminProblemsComponent } from './contest/contest_admin/problems/problems.component';
import { AdminTeamsComponent } from './contest/contest_admin/teams/teams.component';
import { AdminLanguagesComponent } from './contest/contest_admin/languages/languages.component';
import { AdminAdvancedComponent } from './contest/contest_admin/advanced/advanced.component';
import { ContestLiveLogin } from './contest/contest_live/login/login.component';
import { TeamAuthenticatedService } from './services/team_authenticated.service';
import { AdminAuthenticatedService } from './services/admin_authenticated.service';
import { JudgeAuthenticatedService } from './services/judge_authenticated.service';
import { ContestJudgeLogin } from './contest/contest_judge/login/login.component';
import { ContestAdminLogin } from './contest/contest_admin/login/login.component';
import { PageNotFoundComponent } from './page_not_found/page_not_found.component';
import { ContestDoesNotExist } from './contest_does_not_exist/contest_does_not_exist.component';

const appRoutes: Routes = [
    { path: 'create-contest', component: CreateContestComponent },
    {
        path: 'contest/:contestName', component: ContestComponent, children: [
            {
                path: 'judge', component: ContestJudgeComponent, canActivate: [JudgeAuthenticatedService], data: { route: 'judge' }, children: [
                    { path: 'contest-detail', component: JudgeContestDetailComponent },
                    { path: 'submissions', component: JudgeSubmissionsComponent },
                    { path: 'clarifications', component: JudgeClarificationsComponent },
                    { path: 'problems', component: JudgeProblemsComponent },
                    { path: 'standings', component: JudgeStandingsComponent },
                    { path: '', redirectTo: 'contest-detail', pathMatch: 'full' }
                ]
            },
            {
                path: 'admin', component: ContestAdminComponent, canActivate: [AdminAuthenticatedService], data: { route: 'admin' }, children: [
                    { path: 'contest-detail', component: AdminContestDetailComponent },
                    { path: 'problems', component: AdminProblemsComponent },
                    { path: 'teams', component: AdminTeamsComponent },
                    { path: 'languages', component: AdminLanguagesComponent },
                    { path: 'advanced', component: AdminAdvancedComponent },
                    { path: '', redirectTo: 'contest-detail', pathMatch: 'full' }
                ]
            },
            {
                path: '', component: ContestLiveComponent, canActivate: [TeamAuthenticatedService], data: { route: 'live' }, children: [
                    { path: 'contest-detail', component: LiveContestDetailComponent },
                    { path: 'clarifications', component: LiveClarificationsComponent },
                    { path: 'problems', component: LiveProblemsComponent },
                    { path: 'standings', component: LiveStandingsComponent },
                    { path: '', redirectTo: 'contest-detail', pathMatch: 'full' }
                ]
            },
            { path: 'team-login', component: ContestLiveLogin, data: { route: 'login' } },
            { path: 'judge-login', component: ContestJudgeLogin, data: { route: 'login' } },
            { path: 'admin-login', component: ContestAdminLogin, data: { route: 'login' } }
        ]
    },
    { path: 'contest-does-not-exist', component: ContestDoesNotExist },
    { path: '', redirectTo: '/create-contest', pathMatch: 'full' },
    { path: '**', component: PageNotFoundComponent }
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