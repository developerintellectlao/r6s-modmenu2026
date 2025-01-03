import { Component } from "@wordpress/element";
import classnames from "classnames";
import { matchValuesWithCondition, normalizeCondition } from "match-conditions";
import { ControlsPanel as ControlsContainer } from "./ControlsContainer";

export default class Tabs extends Component {
	state = {
		currentTab: 0,
	};

	render() {
		const filteredTabs = this.props.optionGroup.filter(
			(singleTab) =>
				!singleTab.condition ||
				matchValuesWithCondition(
					normalizeCondition(singleTab.condition),
					this.props.value
				)
		);

		const currentTab = filteredTabs[this.state.currentTab];

		return (
			<div className="rishi-tabs">
				<ul>
					{filteredTabs
						.map((singleTab, index) => ({ singleTab, index }))
						.map(({ singleTab, index }) => (
							<li
								key={singleTab.id}
								onClick={() => this.setState({ currentTab: index })}
								className={classnames({
									active: index === this.state.currentTab,
								})}
							>
								{singleTab.title ? singleTab.title : singleTab.id}
							</li>
						))}
				</ul>

				<div className="rishi-current-tab">
					<ControlsContainer
						purpose={this.props.purpose}
						key={currentTab.id}
						onChange={(key, val) => this.props.onChange(key, val)}
						options={currentTab.options}
						value={this.props.value}
						isTabOptions
					/>
				</div>
			</div>
		);
	}
}
