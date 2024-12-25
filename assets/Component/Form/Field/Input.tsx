import {Component, ComponentChild, RenderableProps} from "preact";


type propTypes = {
    title: string;
    name: string;
    id?: string|null;
    type: string;
    value: string|null;
    error: string|null;
    onValueChange: (event: any) => void;
    isRequired: boolean;
    placeholder?: string|null;
}


export default class Input extends Component<propTypes, any> {
    public static defaultProps = {
        type: 'string',
        isRequired: false,
    }

    calculateLabelColor(): string {
        return this.props.error
            ? 'dark:text-red-500 text-red-900'
            : 'dark:text-white text-gray-900';
    }

    calculateInputColor(): string {
        return this.props.error
            ? 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
            : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white';
    }

    render(props?: RenderableProps<propTypes>, state?: Readonly<any>, context?: any): ComponentChild {
        return (
            <div>
                <label
                    htmlFor={this.props.id ?? this.props.name}
                    className={'block mb-2 text-sm font-medium ' + this.calculateLabelColor()}
                >{this.props.title} {this.props.isRequired ? <span className="text-red-400">*</span> : ''}</label>

                <input
                    type={this.props.type}
                    name={this.props.name}
                    id={this.props.id ?? this.props.name}
                    value={this.props.value}
                    onInput={(event) => this.props.onValueChange(event)}
                    placeholder={this.props.placeholder}
                    className={'border text-sm rounded-lg block w-full p-2.5 ' + this.calculateInputColor()}
                    required={this.props.isRequired}
                />

                {this.props.error && <p className="mt-2 text-sm text-red-600 dark:text-red-500">
                    {this.props.error}
                </p>}
            </div>
        );
    }
}