import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Team } from '../../models/team';

const mockData: Team[] = [
    {
        id: 1,
        username: 'user1',
        password: 'password',
        name: 'Team Rocket',
        organization: 'Taylor University',
        coachName: 'Dr. Awesome',
        alternateName: '',
        contestant1Name: '',
        contestant2Name: '',
        contestant3Name: '',
        email: 'rocket@email.com',
    },
    {
        id: 2,
        username: 'user2',
        password: 'password',
        name: 'A-Team',
        organization: 'Ball State University',
        coachName: 'Dr. Good',
        alternateName: '',
        contestant1Name: '',
        contestant2Name: '',
        contestant3Name: '',
        email: 'a.team@email.com',
    }
];

@Injectable()
export class TeamService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockData;
    }

    getAllTeams() {
        return this.baseService.get('');
    }

    getTeam(teamId: number) {
        return this.baseService.get('');
    }

    createTeam(team: Team) {
        return this.baseService.post('', team);
    }

    updateTeam(team: Team) {
        return this.baseService.post('', team);
    }

    deleteTeam(teamId: number) {
        return this.baseService.delete('');
    }
}
