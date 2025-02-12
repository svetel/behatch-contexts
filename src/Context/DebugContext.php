<?php

namespace Behatch\Context;

use Behat\Gherkin\Node\StepNode;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Exception\UnsupportedDriverActionException;

class DebugContext extends BaseContext
{
    /**
     * @var string
     */
    private $screenshotDir;

    public function __construct($screenshotDir = '.')
    {
        $this->screenshotDir = $screenshotDir;
    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then (I )put a breakpoint
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {
        }
        fwrite(STDOUT, "\033[u");

        return;
    }

    /**
     * Saving a screenshot
     *
     * @When I save a screenshot in :filename
     */
    public function iSaveAScreenshotIn($filename)
    {
        sleep(1);
        $this->saveScreenshot($filename, $this->screenshotDir);
    }

    /**
     * @AfterStep
     */
    public function failScreenshots(AfterStepScope $scope)
    {
        if ($scope->getTestResult()->isPassed()) {
            return;
        }

        $this->displayProfilerLink();

        $suiteName = urlencode(str_replace(' ', '_', $scope->getSuite()->getName()));
        $featureTitle = $scope->getFeature()->getTitle() ?: '';
        $featureName = urlencode(str_replace(' ', '_', $featureTitle));

        if ($this->getBackground($scope)) {
            $scenarioName = 'background';
        } else {
            $scenario = $this->getScenario($scope);
            $scenarioTitle = $scenario->getTitle() ?: '';
            $scenarioName = urlencode(str_replace(' ', '_', $scenarioTitle));
        }

        $filename = sprintf('fail_%s_%s_%s_%s.png', time(), $suiteName, $featureName, $scenarioName);
        $this->saveScreenshot($filename, $this->screenshotDir);
    }

    private function displayProfilerLink()
    {
        try {
            $headers = $this->getMink()->getSession()->getResponseHeaders();
            echo "The debug profile URL {$headers['X-Debug-Token-Link'][0]}";
        } catch (\Exception $e) {
            /* Intentionally leave blank */
        }
    }

    /**
     * @param AfterStepScope $scope
     * @return \Behat\Gherkin\Node\ScenarioInterface
     */
    private function getScenario(AfterStepScope $scope)
    {
        $scenarios = $scope->getFeature()->getScenarios();
        foreach ($scenarios as $scenario) {
            $stepLinesInScenario = array_map(
                function (StepNode $step) {
                    return $step->getLine();
                },
                $scenario->getSteps()
            );
            if (in_array($scope->getStep()->getLine(), $stepLinesInScenario)) {
                return $scenario;
            }
        }

        throw new \LogicException('Unable to find the scenario');
    }

    /**
     * @param AfterStepScope $scope
     * @return \Behat\Gherkin\Node\BackgroundNode|bool
     */
    private function getBackground(AfterStepScope $scope)
    {
        $background = $scope->getFeature()->getBackground();
        if (!$background) {
            return false;
        }
        $stepLinesInBackground = array_map(
            function (StepNode $step) {
                return $step->getLine();
            },
            $background->getSteps()
        );
        if (in_array($scope->getStep()->getLine(), $stepLinesInBackground)) {
            return $background;
        }

        return false;
    }

    public function saveScreenshot($filename = null, $filepath = null)
    {
        try {
            parent::saveScreenshot($filename, $filepath);
        } catch (UnsupportedDriverActionException $e) {
            return;
        }
    }
}
