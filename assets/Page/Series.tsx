import {ComponentChild} from "preact";
import Page from "../Component/Abstract/Page";
import {getDashboard, GetDashboardResponse, ListCardResponseItem, search, SearchResponse} from "../Api/Series";
import Loader from "../Component/Loader";
import Input from "../Component/Form/Field/Input";


interface State {
    query: string

    page: number
    items: ListCardResponseItem[]
    pagesCount: number
    resultsCount: number
}


export default class Series extends Page<any, State> {
    querySearchTimeout: ReturnType<typeof setTimeout> = null;

    constructor() {
        super();

        this.querySearchTimeout = null;

        this.state = {
            query: '',
            page: 0,
            items: [],
            pagesCount: 0,
            resultsCount: 0,
        }
    }

    componentDidMount() {
        super.componentDidMount();

        this.loadDashboard(1);
    }

    loadDashboard(page: number) {
        getDashboard(
            page,
            (response: GetDashboardResponse) => {
                this.setState({
                    page: response.data.page,
                    items: response.data.items,
                    pagesCount: response.data.totalPages,
                    resultsCount: response.data.totalResults,
                });
            }
        );
    }

    loadSearch(query: string, page: number) {
        search(
            query,
            page,
            (response: SearchResponse) => {
                this.setState({
                    page: response.data.page,
                    items: response.data.items,
                    pagesCount: response.data.totalPages,
                    resultsCount: response.data.totalResults,
                });
            }
        )
    }

    onQueryChanged(value: string) {
        this.setState({
            ...this.state,
            query: value,
        });

        if (this.querySearchTimeout) {
            clearTimeout(this.querySearchTimeout);
        }

        this.querySearchTimeout = setTimeout(() => {
            if (value === '') {
                this.loadDashboard(1);
            } else {
                this.loadSearch(value, 1);
            }
        }, 1000);
    }

    renderInner(): ComponentChild {
        return (
            <div>
                <div className="p-4 md:p-5">
                    <Input
                        title="Начните вводить поисковую строку (поиск начнётся автоматически):"
                        name="email"
                        type="email"
                        id="login_email"
                        value={this.state.query}
                        onValueChange={(event) => this.onQueryChanged(event.currentTarget.value)}
                        placeholder="Например: Во все тяжкие"
                        autocomplete={false}
                    />
                </div>

                {this.state.items.length <= 0
                    ? <div className="flex items-center justify-center">
                        <Loader />
                    </div>
                    : <div>
                    {/* TODO */}
                    </div>}
            </div>
        );
    }
}