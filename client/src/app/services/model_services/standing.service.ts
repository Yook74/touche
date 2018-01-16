import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Standing } from '../../models/standing';

const mockStandings: Standing[] = [
    {
        rank: 1,
        teamId: 1,
        teamName: 'Good Team',
        problemsCompleted: ['154/20','512/25','123/12','12/52','65/24'],
    },
    {
        rank: 2,
        teamId: 2,
        teamName: 'Bad Team',
        problemsCompleted: ['154/20','512/25','123/12','12/52','65/24'],
    },
    {
        rank: 3,
        teamId: 3,
        teamName: 'Awesome Team',
        problemsCompleted: ['154/20','512/25','123/12','12/52','65/24'],
    }
]

@Injectable()
export class StandingService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockStandings;
    }

    getStandings() {
        return this.baseService.get('');
    }

    createStanding(standing: Standing) {
        return this.baseService.post('', standing);
    }

    updateStanding(standing: Standing) {
        return this.baseService.put('', standing);
    }

    deleteStanding(standingId: number) {
        return this.baseService.delete('');
    }
}
