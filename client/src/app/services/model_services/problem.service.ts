import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Problem } from '../../models/problem';

const mockProblems: Problem[] = [
    {
        id: 1,
        name: 'Problem the First',
        location: 'oneproblem',
        note: 'Laaaaa',
        hasHTML: true,
        hasPDF: true
    },
    {
        id: 2,
        name: 'Problem the Second',
        location: 'twoproblem',
        note: 'A noteworthy note',
        hasHTML: true,
        hasPDF: false
    },
    {
        id: 3,
        name: 'Problem the Third',
        location: 'threeproblem',
        note: 'A notable note',
        hasHTML: false,
        hasPDF: true
    }
]

@Injectable()
export class ProblemService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockProblems;
    }

    getProblems() {
        return this.baseService.get('problems');
    }

    createProblem(problem: Problem) {
        return this.baseService.post('', problem);
    }

    updateProblem(problem: Problem) {
        return this.baseService.put('', problem);
    }

    deleteProblem(problemId: number) {
        return this.baseService.delete('');
    }
}
