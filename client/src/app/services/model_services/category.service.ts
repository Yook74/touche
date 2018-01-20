import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Category } from '../../models/category';
import { Team } from '../../models/team';

const mockCategories: Category[] = [
    {
        id: 1,
        name: 'Winners'
    },
    {
        id: 2,
        name: 'Losers'
    }
];

const mockTeams: Team[] = [
    {
        id: 1,
        username: 'user1',
        password: 'password',
        name: 'Team Rocket',
        organization: '',
        coachName: '',
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
        organization: '',
        coachName: '',
        alternateName: '',
        contestant1Name: '',
        contestant2Name: '',
        contestant3Name: '',
        email: 'a.team@email.com',
    }
];

@Injectable()
export class CategoryService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockCategories;
    }

    getMockTeamsInCategory(categoryId: number) {
        return mockTeams;
    }

    getAllCategories() {
        return this.baseService.get('');
    }

    getTeamsInCategory(categoryId: number) {
        return this.baseService.get('');
    }

    createCategory(categoryName: string) {
        return this.baseService.post('', categoryName);
    }
}
