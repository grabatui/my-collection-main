import {Component, ComponentChild, ComponentChildren, RenderableProps} from "preact";


type propTypes = {
    isOpen: boolean;
    onClose: () => void;
    title?: string;
    children?: ComponentChildren,
}

export default class Modal extends Component<propTypes, any> {
    render(props?: RenderableProps<propTypes>): ComponentChild {
        return (
            <div
                className={`fixed inset-0 flex justify-center items-center transition-colors ` + (props.isOpen ? 'visible bg-black/20' : 'invisible')}
                onClick={this.props.onClose}
            >
                <div className="relative p-4 w-full max-w-md max-h-full" onClick={(e) => e.stopPropagation()}>
                    <div className="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div
                            className="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600"
                        >
                            {props.title && (
                                <h3 className="text-xl font-semibold text-gray-900 dark:text-white">{props.title}</h3>
                            )}

                            <button
                                type="button"
                                className="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="authentication-modal"
                                onClick={this.props.onClose}
                            >
                                <svg
                                    className="w-3 h-3"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 14 14"
                                >
                                    <path
                                        stroke="currentColor"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                                    />
                                </svg>

                                <span className="sr-only">Close modal</span>
                            </button>
                        </div>

                        {props.children}
                    </div>
                </div>
            </div>
        );
    }
}
