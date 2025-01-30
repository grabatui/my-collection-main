import {ComponentChild, Fragment} from "preact";
import {getMetadata} from "../Api/User";
import {clearUser, getAccessToken} from "../helpers/api";
import {menuProfileOpened} from "../Signal/MenuSignal";
import Page from "../Component/Abstract/Page";
import {getDashboard, GetDashboardResponse, ListCardResponseItem} from "../Api/Series";
import Loader from "../Component/Loader";
import {Link} from "preact-router";
import {Popover} from "flowbite";


interface State {
    items: ListCardResponseItem[]
}

export default class Home<TPropTypes> extends Page<TPropTypes, State> {
    constructor() {
        super();

        this.state = {
            items: [],
        }

        if (getAccessToken()) {
            getMetadata();
        } else {
            clearUser();
        }
    }

    componentDidMount() {
        super.componentDidMount();

        getDashboard(
            1,
            (response: GetDashboardResponse) => {
                this.setState({items: response.data.items});
            }
        );
    }

    componentDidUpdate() {
        this.state.items.forEach((item: ListCardResponseItem) => {
            const popoverCard = document.getElementById('popover-card-' + item.id);
            const popoverCardTrigger = document.getElementById('popover-card-trigger-' + item.id);

            if (popoverCard && popoverCardTrigger) {
                new Popover(
                    popoverCard,
                    popoverCardTrigger
                );
            }
        });
    }

    closeAllOpenedMenu(): void {
        menuProfileOpened.value = false;
    }

    renderInner(): ComponentChild {
        return this.state.items.length <= 0
            ? <div className="flex items-center justify-center">
                <Loader />
            </div>
            : <div>
                <div className="grid grid-cols-5 gap-4 xl:grid-cols-10">
                    {this.state.items.map((item: ListCardResponseItem) => (
                        <Fragment>
                            <div id={"popover-card-trigger-" + item.id}>
                                <Link href={"/series/" + item.slug}>
                                    <img src={item.posterPath} alt={item.title} />
                                </Link>
                            </div>

                            {this.renderCardInfo(item)}
                        </Fragment>
                    ))}
                </div>

                <div className="flex items-center justify-center">
                    <Link
                        href={"/series"}
                        className="px-4 py-2 my-4 bg-indigo-500 hover:bg-indigo-600 text-gray-50 rounded-xl flex items-center gap-2"
                    >К сериалам</Link>
                </div>
            </div>;
    }

    renderCardInfo(item: ListCardResponseItem): ComponentChild {
        const firstAirDate = new Date(item.firstAirDate);

        return (
            <div
                id={"popover-card-" + item.id}
                className="absolute z-10 invisible inline-block w-80 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800"
            >
                <div className={"px-3 py-2 bg-gray-100 border-b border-gray-200 dark:border-gray-600 dark:bg-gray-700 " + (item.overview ? 'rounded-t-lg' : 'rounded-lg')}>
                    <h3 className="font-semibold text-gray-900 dark:text-white">{item.title}</h3>
                    {item.originalTitle && (
                        <p className="text-gray-500 dark:text-white">{item.originalTitle}</p>
                    )}
                    <p className="text-gray-500 dark:text-white">{firstAirDate.getFullYear()}</p>
                </div>

                {item.overview && (
                    <div className="px-3 py-2">
                        <p dangerouslySetInnerHTML={{__html: item.overview}} />
                    </div>
                )}
            </div>
        );
    }
}
