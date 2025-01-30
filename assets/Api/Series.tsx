import {callEndpoint} from "./callEndpoint";


export interface ListCardResponseItem {
    id: number
    title: string
    originalTitle: string
    overview: string
    posterPath: string
    voteAverage: number
    countries: string[]
    genres: string[]
    firstAirDate: string
    slug: string
}

export interface GetDashboardResponse {
    data: {
        items: ListCardResponseItem[]
    }
}

export const getDashboard = (
    page: number,
    onSuccess?: (response: GetDashboardResponse) => void,
    onError?: (reason: any) => void,
) => {
    callEndpoint('/api/v1/series/dashboard', 'GET', {page: page}, onSuccess, onError)
};