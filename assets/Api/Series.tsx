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
        page: number
        items: ListCardResponseItem[]
        totalPages: number
        totalResults: number
    }
}

export interface SearchResponse {
    data: {
        page: number
        items: ListCardResponseItem[]
        totalPages: number
        totalResults: number
    }
}

export const getDashboard = (
    page: number,
    onSuccess?: (response: GetDashboardResponse) => void,
    onError?: (reason: any) => void,
) => {
    callEndpoint('/api/v1/series/dashboard', 'GET', {page: page}, onSuccess, onError)
};

export const search = (
    query: string,
    page: number,
    onSuccess?: (response: SearchResponse) => void,
    onError?: (reason: any) => void,
) => {
    callEndpoint('/api/v1/series/search', 'GET', {query: query, page: page}, onSuccess, onError)
}